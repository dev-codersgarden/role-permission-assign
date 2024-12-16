<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { reactive, computed } from 'vue';

import axios from 'axios';
import { toast } from 'vue3-toastify';

const { t } = useI18n();

const props = defineProps({
    role: {
        type: Object,
    },
    permissionGroups: {
        type: Array,
    },
    assignedPermissions: {
        type: Array, // IDs of the assigned permissions
    },
});

// const breadcrumbData = computed(() => ({
//     pageTitle: "Role Permissions" + ' - ' + props.role.name,
//     pageLinks: [
//         {
//             name: 'Roles',
//             route: 'roles.index',
//         },
//         {
//             name: 'Role Permissions',
//         },
//     ],
// }));

// Check if permission is already assigned
const isAssigned = (permissionId) => {
    
    return props.assignedPermissions.includes(permissionId);
};

// Handle checkbox toggle
const togglePermission = async (permissionId, checked) => {

    try {
        const response = await axios.post('/api/permissions/assign-or-remove', {
            role_id: props.role.id,
            permission_id: permissionId,
            assign: checked,
        });

        if(response.data.status == 'assigned') {
            toast.success(response.data.message);
        }

        if(response.data.status == 'removed') {
            toast.success(response.data.message);
        }
        
        console.log('Response:', response.data);
    } catch (error) {
        console.error('Error:', error.response.data);
    }
};
</script>

<template>

    <!-- <Head title="Permissions Assignment" /> -->
    <AuthenticatedLayout>
        <div class="row justify-content-center add-edit-page">
            <div class="container-fluid my-5">

                <div class="row  justify-content-start">
                    <div class="col-md-4 col-lg-6 col-xxl-7 mb-3">
                        <div class="page-title">
                            <!-- <Breadcrumb :data="breadcrumbData" /> -->
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-6 col-xxl-5 mb-3">
                        <Link class="btn btn-outline-danger float-end text-dark" :href="route('roles.index')">
                        <i class="  ri-arrow-go-back-line fs-5"></i>
                        Back
                        </Link>
                    </div>
                </div>
            



            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body p-4" v-for="(permissionGroup) in permissionGroups"
                            :key="permissionGroup.id">
                            <strong class="card-title">{{ permissionGroup.name }}</strong>

                            <div class="form-card mt-3">
                                <div class="d-grid gap-3" style="grid-template-columns: repeat(4, 1fr);">
                                    <div class="form-check form-switch form-switch-md"
                                        v-for="permission in permissionGroup.permissions" :key="permission.id">
                                        <input class="form-check-input" type="checkbox" :id="permission.id"
                                            :value="permission.id" :checked="isAssigned(permission.id)"
                                            @change="togglePermission(permission.id, $event.target.checked)" />
                                        <span class="ms-2 text-dark text-capitalize ">
                                            <b>{{ permission.name }}</b>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </AuthenticatedLayout>
</template>
<style scoped>
.form-check-input:checked {
    background-color: #f7d794 !important;
}

.add-edit-page input,
.add-edit-page textarea,
.add-edit-page .multiselect {
    border: 1px solid #f7d794 !important;
}
</style>