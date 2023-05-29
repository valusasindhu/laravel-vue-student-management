<template>
    <div class="flex items-center justify-between mb-3">
        <h1 class="text-3xl font-semibold">Students</h1>
        <button type="button"
                @click="showAddNewModal()"
                class="py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
            Add new Student
        </button>
    </div>
    <StudentsTable @clickEdit="editStudent"/>
    <StudentModal v-model="showStudentModal" @close="onModalClose" :student="studentModel"/>
</template>

<script setup>
import {computed, ref} from "vue";
import store from "../../store";
import StudentModal from "./StudentModal.vue";
import StudentsTable from "./StudentsTable.vue";

const DEFAULT_STUDENT = {
    id: '',
    first_name: '',
    last_name: '',
    image: '',
    class: ''
}

const students = computed(() => store.state.students);

const studentModel = ref({...DEFAULT_STUDENT})
const showStudentModal = ref(false);

function showAddNewModal() {
    showStudentModal.value = true
}

function editStudent(student) {
    store.dispatch('getStudent', student.id)
        .then(({data}) => {
            studentModel.value = data
            showAddNewModal();
        })
}

function onModalClose() {
    studentModel.value = {...DEFAULT_STUDENT}
}
</script>

<style scoped>

</style>