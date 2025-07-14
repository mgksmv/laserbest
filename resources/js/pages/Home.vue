<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import VisitModal from '@/components/visits/VisitModal.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import moment from 'moment';
import { useModal } from '@/composables/useModal';
import { BookStaff } from '@/types/bookStaff';
import { Visit, VisitStatus } from '@/types/visit.d';

const props = defineProps<{
    timeIntervals: any,
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

const visitModal = useModal();

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
        <div class="timeline-table">
            <div class="timeline-table__days">
                <div class="timeline-table__day timeline-table__day--empty"></div>
                <div v-for="staff in bookStaff" :key="staff.id" class="timeline-table__day">
                    {{ staff.name }}
                </div>
            </div>
            <div class="timeline-table__content">
                <div class="timeline-table__intervals">
                    <div v-for="(timeInterval, key) in timeIntervals" :key="key" class="timeline-table__interval">
                        <div>
                            <span>{{ getHoursFromTimeInterval(timeInterval) }}</span>
                            {{ getMinutesFromTimeInterval(timeInterval) }}
                        </div>
                        <div v-for="partOfHour in partsOfHour - 1" :key="partOfHour">
                            {{ getMinutesInterval(timeInterval, partOfHour) }}
                        </div>
                    </div>
                </div>

                <div class="timeline-table__list">
                    <div v-for="staff in bookStaff" :key="staff.id" class="timeline-table__list-day">
                        <div
                            v-for="timeInterval in timeIntervals"
                            :key="timeInterval"
                            class="timeline-table__list-hour"
                        >
                            <span v-for="slot in slotsInOneHour" :key="slot" @click="visitModal.open" class="noselect">
                                {{ getSlotTime(timeInterval, slot - 1) }}
                            </span>
                        </div>

                        <template v-if="staff.visits">
                            <template v-for="visit in staff.visits" :key="visit.id">
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
                                        <span class="timeline-table__icon" :title="visit.status_title"></span>
                                    </div>
                                    <div class="timeline-table__visit-description">
                                        <p class="client-name">{{ visit.client.name }}</p>
                                        <p v-for="phone in visit.client.phones" :key="phone">
                                            📞 {{ phone }}
                                        </p>
                                        <p v-for="service in visit.services" :key="service.id">
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

        <VisitModal v-if="visitModal.isOpened.value" @close-modal="visitModal.close" />
    </AppLayout>
</template>
