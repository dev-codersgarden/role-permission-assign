<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Pagination from '@/Components/Misc/Pagination.vue';
import { useGeneral } from "@/Composable/General";

import { useForm } from '@inertiajs/vue3';
import { reactive, watch,computed } from 'vue';
import { throttle } from 'lodash';
import Breadcrumb from '@/Components/Misc/Breadcrumb.vue';

import { useI18n } from 'vue-i18n';
const { t } = useI18n();

const { confirmDelete, checkPermission, asset } = useGeneral();

const props = defineProps({

    roles: {
        type: Object,
    },

    request: {
        type: Object,
    },
});
const form = useForm({
    sort: props.request.sort ?? 'id',
    direction: props.request.direction ?? 'desc',
    search: props.request.search ?? null,
});
watch(() => form.search,
    throttle(() => {
        form.get(route(route().current()), {
            preserveState: true,
        });
    }, 500),
    { deep: true }
);

const sort = (column) => {
    form.sort = column;
    form.direction = form.direction == "asc" ? 'desc' : 'asc';
    form.get(route(route().current()), {
        preserveState: false,
    });
}
const breadcrumbData = computed(() => ({
    pageTitle: 'Roles',
    pageLinks: [
        {
            "name": 'Roles',
            "route": "roles.index",
        },
        {
            "name": 'Index',
        }
    ]
}));

</script>

<template>

    <Head title="Roles" />

    <AuthenticatedLayout>
        <div class="row justify-content-center">
            <div class="container-fluid my-5">

                <div class="row  justify-content-end">
                    <div class="col-md-4 col-lg-6 col-xxl-7 mb-3">
                        <div class="page-title">

                            <Breadcrumb :data="breadcrumbData" />
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-6 col-xxl-5 mb-3">
                        <div class="header-right">
                            <div class="search-box">
                                <img :src="asset('assets/svg/search-icon.svg')" class="first-search-icon">
                                <input type="text" class="form-control" v-model="form.search"
                                  placeholder="Search">
                                <Link :href="route(route().current())" class="last-cross-icon">
                                <img :src="asset('assets/svg/cross-icon.svg')" style="height: 25px;">
                                </Link>
                            </div>

                            <Link :href="route('roles.create')" class="btn btn-primary float-end"
                                v-if="checkPermission('roles.create')">
                            <img :src="asset('assets/svg/plus-icon.svg')">
                            Create Role
                            </Link>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive table-card p-2">
                            <table class="table table-borderless align-middle table-nowrap mb-0">
                                <thead>
                                    <tr class="align-middle ">
                                        <th scope="col" @click="sort('id')" class="cursor-pointer"
                                            :class="form.sort == 'id' ? 'text-danger' : ''">#</th>
                                        <th scope="col" class="cursor-pointer" @click="sort('name')"
                                            :class="form.sort == 'name' ? 'text-danger' : ''">
                                            Name 
                                        </th>
                                        <td scope="col" align="center">Actions </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="align-middle" v-for="(role, index) in roles.data" :key="role.id">
                                        <td scope="row"><b>{{ role.id }} </b></td>
                                        <td>
                                            <b class="fs-16 text-dark text-capitalize">
                                                {{ role.name }}
                                            </b>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center align-items-center">
                                                <Link class="btn p-0 text-dark" title="Edit"
                                                    v-if="checkPermission('roles.edit')"
                                                    :href="route('roles.edit', role.id)">
                                                <i class="bx bx-edit me-1 cursor-pointer fs-2"></i>
                                                </Link>

                                                <button type="button" title="Delete"
                                                    v-if="checkPermission('roles.destroy')"
                                                    @click="confirmDelete(route('roles.destroy', role.id))"
                                                    class="btn p-0 text-dark">
                                                    <i class="bx bx-trash cursor-pointer fs-2"></i>
                                                </button>
                                                <Link class="btn p-0 text-dark"
                                                    :title="t('roles.hoverTitle.assignPermission')"
                                                    v-if="checkPermission('roles.permissions')"
                                                    :href="route('roles.permissions', role.id)">
                                                <i class=" bx bx-pin cursor-pointer fs-2"></i>
                                                </Link>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tr v-if="roles.data.length <= 0">
                                    <td scope="row" colspan="7" class="text-center">
                                        <h5 class="text-danger">No Data Found</h5>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!-- <div class="col-md-12">
                        <Pagination :links="roles.links" v-if="roles.data.length > 0" />
                    </div> -->
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

</template>