<script setup lang="ts">
import { ref, watch } from 'vue';
import { router, Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import VisitModal from '@/components/visits/VisitModal.vue';
import { type BreadcrumbItem } from '@/types';
import moment from 'moment';
import { useModal } from '@/composables/useModal';
import { BookStaff } from '@/types/bookStaff';
import { Visit, VisitStatus } from '@/types/visit.d';
import DatePicker from 'primevue/datepicker';
import Select from 'primevue/select';
import IftaLabel from 'primevue/iftalabel';

const props = defineProps<{
    salons: any,
    timeIntervals: string[],
    partsOfHour: number,
    increaseInMinutes: number,
    slotsInOneHour: number,
    bookStaff: BookStaff[],
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Главная',
        href: '/'
    }
];

const params = new URLSearchParams(location.search);

const salonId = ref<string>(params.get('salonId') ?? props.salons[0].id);
const date = ref<Date>(params.get('date') ? new Date(params.get('date')) : new Date());
const currentStaffId = ref<string | null>(null);

const visitModal = useModal();

watch(salonId, (value) => {
    router.get(route('home'), { salonId: value, date: moment(date.value).format('YYYY-MM-DD') });
});

watch(date, (value) => {
    date.value = value;
    router.get(route('home'), { salonId: salonId.value, date: moment(value).format('YYYY-MM-DD') });
});

function getHoursFromTimeInterval(timeInterval: string) {
    return moment(timeInterval, 'HH:mm').format('HH');
}

function getMinutesFromTimeInterval(timeInterval: string) {
    return moment(timeInterval, 'HH:mm').format('mm');
}

function getMinutesInterval(timeInterval: string, partOfHour: number) {
    return moment(timeInterval, 'HH:mm')
        .add((props.increaseInMinutes ?? 0) * partOfHour, 'minutes')
        .format('mm');
}

function getSlotTime(startTime: string, slotOffset: number) {
    return moment(startTime, 'HH:mm').add(slotOffset * 5, 'minutes').format('HH:mm');
}

function getVisitClasses(visit: Visit) {
    const classList = ['timeline-table__visit'];

    switch (visit.status) {
    case VisitStatus.Archival:
        classList.push('archival');
        break;
    case VisitStatus.NotCame:
        classList.push('not-came');
        break;
    case VisitStatus.Expected:
        classList.push('expected');
        break;
    case VisitStatus.Came:
        classList.push('came');
        break;
    case VisitStatus.Finished:
        classList.push('finished');
        break;
    case VisitStatus.Canceled:
        classList.push('canceled');
        break;
    }

    return classList;
}

function canBookRecordInTime(staff: any, time: string) {
    const checkDate = moment(date.value).format('YYYY-MM-DD');
    const fullTime = moment(`${checkDate} ${time}`, 'YYYY-MM-DD HH:mm').format('YYYY-MM-DDTHH:mm:ss');

    return staff.upcoming_times?.includes(fullTime);
}

function handleSlotClick(staffId: string) {
    currentStaffId.value = staffId;
    visitModal.open();
}

function handleVisitMouseenter(event: MouseEvent) {
    const parent = event.target.closest('.timeline-table__visit');
    parent.style.height = parent.scrollHeight + 'px';
}

function handleVisitMouseleave(event: MouseEvent, height: number) {
    const parent = event.target.closest('.timeline-table__visit');
    parent.style.height = height + 'px';
}
</script>

<template>
    <Head title="Home" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="w-full grid auto-rows-min gap-4 md:grid-cols-2">
            <div class="w-full">
                <div class="timeline-table">
                    <div class="timeline-table__days">
                        <div class="timeline-table__day timeline-table__day--empty" />
                        <div
                            v-for="staff in bookStaff"
                            :key="staff.id"
                            class="timeline-table__day"
                        >
                            {{ staff.name }}
                        </div>
                    </div>
                    <div class="timeline-table__content">
                        <div class="timeline-table__intervals">
                            <div
                                v-for="(timeInterval, key) in timeIntervals"
                                :key="key"
                                class="timeline-table__interval"
                            >
                                <div>
                                    <span>{{ getHoursFromTimeInterval(timeInterval) }}</span>
                                    {{ getMinutesFromTimeInterval(timeInterval) }}
                                </div>
                                <div
                                    v-for="partOfHour in partsOfHour - 1"
                                    :key="partOfHour"
                                >
                                    {{ getMinutesInterval(timeInterval, partOfHour) }}
                                </div>
                            </div>
                        </div>

                        <div class="timeline-table__list">
                            <div
                                v-for="staff in bookStaff"
                                :key="staff.id"
                                class="timeline-table__list-day"
                            >
                                <div
                                    v-for="timeInterval in timeIntervals"
                                    :key="timeInterval"
                                    class="timeline-table__list-hour"
                                >
                                    <span
                                        v-for="slot in slotsInOneHour"
                                        :key="slot"
                                        class="noselect"
                                        :class="{ disabled: !canBookRecordInTime(staff, getSlotTime(timeInterval, slot - 1)) }"
                                        @click="handleSlotClick(staff.id)"
                                    >
                                        {{ getSlotTime(timeInterval, slot - 1) }}
                                    </span>
                                </div>

                                <template v-if="staff.visits">
                                    <template
                                        v-for="visit in staff.visits"
                                        :key="visit.id"
                                    >
                                        <div
                                            :class="getVisitClasses(visit)"
                                            :style="`
                                                height: ${visit.height}px;
                                                top: ${visit.top}px;
                                                width: ${100 - visit.nesting}%;
                                            `"
                                            @mouseenter="handleVisitMouseenter"
                                            @mouseleave="handleVisitMouseleave($event, visit.height)"
                                        >
                                            <div
                                                class="timeline-table__visit-time"
                                                :style="visit.time_min_height ? 'min-height: 15px;' : null"
                                            >
                                                {{ visit.start_hours }}—{{ visit.end_hours }}
                                                <span
                                                    class="timeline-table__icon"
                                                    :title="visit.status_title"
                                                />
                                            </div>
                                            <div class="timeline-table__visit-description">
                                                <p class="client-name">
                                                    {{ visit.client.name }}
                                                </p>
                                                <p
                                                    v-for="phone in visit.client.phones"
                                                    :key="phone"
                                                >
                                                    📞 {{ phone }}
                                                </p>
                                                <p
                                                    v-for="service in visit.services"
                                                    :key="service.id"
                                                >
                                                    {{ service.title }}
                                                </p>
                                            </div>
                                        </div>
                                    </template>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full flex flex-col gap-4">
                <IftaLabel>
                    <Select
                        id="salon"
                        v-model="salonId"
                        :options="salons"
                        option-label="name"
                        option-value="id"
                        placeholder="Выберите салон"
                        class="w-full md:w-56"
                    />
                    <label for="salon">Салон</label>
                </IftaLabel>

                <DatePicker
                    v-model="date"
                    inline
                    size="small"
                    class="w-full sm:w-[30rem]"
                />
            </div>
        </div>

        <VisitModal
            v-if="visitModal.isOpened.value"
            :salon-id="salonId"
            :staff-id="currentStaffId"
            @close-modal="visitModal.close"
        />
    </AppLayout>
</template>
