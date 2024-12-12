<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Breadcrumb from '@/Components/Misc/Breadcrumb.vue';
import { reactive, computed } from 'vue';
import { useGeneral } from '@/Composable/General';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const { asset } = useGeneral();


const form = useForm({
    name: '',

});

const handleSubmit = () => {
    form.post(route('roles.store'), {
        onFinish: () => {
            form.reset();
        },
    });
};
const breadcrumbData = computed(() => ({
    pageTitle: "Roles",
    pageLinks: [
        {
            "name": 'Roles',
            "route": "roles.index",
        },
        {
            "name": 'Create Role',
        }
    ]
}));
</script>

<template>

    <Head title="Create Role" />

    <AuthenticatedLayout>
        <div class="row justify-content-center add-edit-page">
            <div class="container-fluid my-5">
                <form @submit.prevent="handleSubmit">

                    <div class="row  justify-content-start">
                        <div class="col-md-4 col-lg-6 col-xxl-7 mb-3">
                            <div class="page-title">
                                <Breadcrumb :data="breadcrumbData" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body p-4">
                                    <div class="row  justify-content-end mb-3">
                                        <div class="col-md-8 col-lg-6 col-xxl-5">
                                            <div class="header-right">
                                                <button type="submit" class="btn btn-primary float-end"
                                                    :disabled="form.processing">
                                                    <img :src="asset('assets/svg/save-icon.svg')">
                                                    Save
                                                </button>

                                                <Link class="btn btn-outline-danger float-end text-dark"
                                                    :href="route('roles.index')" :disabled="form.processing">
                                                <i class="  ri-arrow-go-back-line fs-5"></i>
                                                Back
                                                </Link>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-card">
                                        <div class="row">
                                            <div class="col-md-7 col-lg-9">
                                                <div class="row">
                                                    <div class="col-12 col-lg-6 col-md-12 col-sm-12 mb-2">
                                                        <div class="mb-3">
                                                            <label for="name">Role Name: <span
                                                                    class="text-danger">*</span></label>
                                                            <input id="name" type="text" class="form-control"
                                                                v-model="form.name"
                                                                placeholder="Enter Role Name" autofocus />
                                                            <div class="text-danger" v-if="form.errors.name">{{
                                                                form.errors.name }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
