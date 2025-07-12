<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import VisitModal from '@/components/visits/VisitModal.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import moment from 'moment';
import { useModal } from '@/composables/useModal';
// import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const props = defineProps({
    timeIntervals: Object,
    partsOfHour: Number,
    increaseInMinutes: Number,
    slotsInOneHour: Number,
    bookStaff: Object
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Главная',
        href: '/'
    }
];

const visitModal = useModal();

// function formatDate(date: string) {
//     return moment(date).format('D MMM YYYY');
// }

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

// function getRecordClasses(record: any) {
//     const classes = ['timeline-table__record'];
//
//     if (record.is_smaller) {
//         classes.push('timeline-table__record--smaller');
//     }
//
//     if (record.status_class_name) {
//         classes.push(record.status_class_name);
//     }
//
//     return classes.join(' ');
// }
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
                                    class="timeline-table__record"
                                    :style="`
                                        height: ${visit.height}px;
                                        top: ${visit.top}px;
                                        width: ${100 - visit.nesting}%;
                                    `"
                                >
                                    <div
                                        class="timeline-table__record-time"
                                        :style="visit.time_min_height ? 'min-height: 15px;' : null"
                                    >
                                        {{ visit.start_hours }}—{{ visit.end_hours }}
                                        <span class="timeline-table__icon" :title="visit.status_title"></span>
                                    </div>
                                    <div class="timeline-table__record-description">
                                        <p class="client-name">{{ visit.client.name }}</p>
                                        <p v-for="phone in visit.client.phones" :key="phone">
                                            📞 {{ phone }}
                                        </p>
                                        <p>{{ visit.services_as_string }}</p>
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
