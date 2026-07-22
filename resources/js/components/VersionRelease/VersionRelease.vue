<template>
	<div>
		<!--begin::Nav Bar-->
        <loader v-if="loading"></loader>
        <nav class="navbar navbar-default top-navbar" role="navigation" v-if="isAuthenticated">
            <div class="row">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-8">
                            <span class="span-username">Hi, {{ this.userName }}</span>
                        </div>
                        <div class="col-md-4">
                            <navbarRight :user-role-level="userRoleLevel" :user-id="userId"></navbarRight>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
		<!--end::Nav Bar-->
		<!--begin::Header-->
        <div class="div-spacing" v-if="isAuthenticated"></div>     
        <div class="header" v-if="isAuthenticated">
            <h1 class="page-header">
                <img class="lafil-logo" :src="logoLink">
                <b>5S PORTAL - VERSION RELEASE</b>
            </h1>
            <breadcrumb :user-role-level="userRoleLevel"></breadcrumb>
        </div>
		<!--end::Header-->

		<div class="container-fluid my-4">
			<div class="text-right">
				<div class="btn btn-white my-4" v-if="isAdministrator" @click="toggleModal('form_modal', true)">Add New Version</div>
			</div>
			<div class="d-flex flex-row align-items-stretch">
				<!--begin::Aside-->
				<div class="flex-row col-3 offcanvas-mobile w-300px w-xl-350px min-h-550px d-flex" id="kt_profile_aside">
					<!--begin::Profile Card-->
					<div class="card card-custom card-stretch shadow-sm w-100 h-100">
						<div class="card-header border-0 pt-10 pl-15">
							<h3 class="card-title font-weight-bolder text-dark">Version Release</h3>
						</div>
						<!--begin::Body-->
						<div class="card-body pt-4 table-responsive" style="max-height: 400px;">

							<!--begin::Nav-->
							<div class="navi navi-bold navi-hover navi-active navi-link-rounded pb-8">
								<div class="navi-item mb-1" v-for="(item, index) in filteredQueues" :key="index">
									<a class="navi-link cursor-pointer py-1 pr--2 version-link-row"
									   :class="{ 'version-link-active': selectedVersion.id === item.id }"
									   @click="viewVersion(item)">
										<span class="navi-icon mr-2">
											<span class="svg-icon">
												<!--begin::Svg Icon | path:assets/media/svg/icons/Design/Layers.svg-->
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<polygon points="0 0 24 0 24 24 0 24"></polygon>
														<path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero"></path>
														<path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3"></path>
													</g>
												</svg>
												<!--end::Svg Icon-->
											</span>
										</span>
										<span class="navi-text font-size-lg version-link-text">{{ `Vsn ${item.version}` }}</span>
										<span class="d-flex align-items-center ml-auto">
											<span class="navi-label" v-if="index == 0">
												<span class="label p-1 label-inline text-white bg-success rounded">new</span>
											</span>
											<span class="text-danger pl-2" @click.stop="deleteVersion(item)" v-if="isAdministrator" role="button" tabindex="0">
												<i class="fa fa-trash text-danger"></i>
											</span>
										</span>
									</a>
								</div> 

								<div v-if="!items.length">
									<span class="spinner spinner-primary mr-10"></span>
									<span>Loading Data...</span>
								</div>
							</div>
							<!--end::Nav-->
						</div>
						<!--end::Body-->
						<div class="card-footer py-2">
							<!--begin::Pagination-->
                         	<div class="d-flex justify-content-center align-items-center mb-2" v-if="filteredQueues.length">
                         	    <button v-if="!onFirstPage" class="btn btn-muted btn-sm" v-on:click="currentPage--">
 									<i class="fa fa-angle-left"></i></button>
                         	    <span class="text-dark">Page {{ this.currentPage + 1 }} of {{ this.totalPages }}</span>
                         	    <button v-if="!onLastPage" class="btn btn-muted btn-sm" v-on:click="currentPage++">
 									<i class="fa fa-angle-right"></i></button>
                         	</div>
							<!--end::Pagination-->
						</div>
					</div>
					<!--end::Profile Card-->
				</div>
				<!--end::Aside-->
				<!--begin::Content-->
				<div class="flex-row col-9 p-0 d-flex">
					<!--begin::Advance Table: Widget 7-->
					<div class="card card-custom card-stretch px-2 shadow-sm w-100 h-100">
						<!--begin::Header-->
						<div class="card-header border-0 pt-10">
							<h3 class="card-title align-items-start flex-column">
								<span class="card-label font-weight-bold font-size-h4 text-dark-75" >Vsn {{ selectedVersion.version || "0000.00.00" }}</span>
								<span class="text-muted mt-3 font-weight-bold font-size-sm">Release Date: {{ selectedVersion.release_date || "0000-00-00"}}</span>
							</h3>
						</div>
						<!--end::Header-->
						<!--begin::Body-->
						<div class="card-body table-responsive" style="max-height: 400px;">
							<div class="mb-10" v-if="!isEmpty(selectedVersion)">
								<!--New features-->
								<VersionItems type="new" :version_release_id="selectedVersion.id"
								:items="selectedVersion.release_note.new" @submitSuccess="fetchList"
								:lastItem="lastItem" :isAdministrator="isAdministrator"/>
								<!--Updates-->
								<VersionItems type="updates" :version_release_id="selectedVersion.id"
								:items="selectedVersion.release_note.updates" @submitSuccess="fetchList"
								:lastItem="lastItem" :isAdministrator="isAdministrator"/>
								<!--Fixes-->
								<VersionItems type="fixes" :version_release_id="selectedVersion.id"
								:items="selectedVersion.release_note.fixes" @submitSuccess="fetchList"
								:lastItem="lastItem" :isAdministrator="isAdministrator"/>
							</div>
							<div v-else>
								<span class="spinner spinner-primary mr-10"></span>
								<span>Loading Data...</span>
							</div>
						</div>
						<!--end::Body-->
					</div>
					<!--end::Advance Table Widget 7-->
				</div>
				<!--end::Content-->
			</div>
			<div class="card card-custom card-stretch my-3">
				<div class="card-body">
					<!--begin::Feedbacks Table-->
					<div v-if="!isEmpty(selectedVersion.feedbacks)">
						<h3>Vsn {{ selectedVersion.version || "0000.00.00" }} Feedback</h3>
						<div style="max-height: 360px" class="table-responsive my-4">
							<table class="table table-head-custom table-vertical-center mb-4">
 							    <thead>
 							    	<tr class="text-uppercase">
										<th class="col-2">Username</th>
										<th class="col-2">Email</th>
 										<th class="col-2">Date</th>
 										<th class="col-8">Feedback Notes</th>
 							    	</tr>
 							    </thead>
 							    <tbody>
								        <tr v-for="feedback in selectedVersion.feedbacks" :key="feedback.id">
										<td>{{ feedback.user.name }}</td>
										<td>{{ feedback.user.email }}</td>
 									    <td>{{ feedback.created_at.slice(0, 10) }}</td>
 									    <td>{{ feedback.feedback }}</td>
										<td>
											<a href="javascript:;" @click="deleteFeedback(feedback.id)">
												<i class="fa fa-trash font-size-sm text-danger"></i>
											</a>
										</td>
 							        </tr>
 							    </tbody>
 							</table>
						</div>
					</div>
					<!--end::Feedbacks Table-->
					<span>Comments? Suggestions? </span>
					<a href="javascript:;" @click="toggleModal('feedback_modal', true)">Send your feedback!</a>
				</div>
			</div>
		</div>

		<!-- begin:Add Modal -->
		<form-modal
			:data="data"
			:formErrors="errors"
			:formAction="formAction"
			@submit="submit"/>
		<!-- end:Add Modal -->
		<!-- begin:Feedback Modal -->
		<feedback-modal
			:versionReleaseId="selectedVersion.id"
			:authenticated="isAuthenticated"
			:formAction="formAction"
			:feedbackId="feedbackId"
			@formClose="toggleModal('feedback_modal', false)"/>
		<!-- end:Feedback Modal -->
	</div>
</template>

<script>
	import FormModal from './FormModal.vue';
	import FeedbackModal from './FeedbackModal.vue';
    import loader from '../Loader';
    import navbarRight from '../NavbarRight';
	import VersionItems from './VersionItems.vue';
	import Swal from 'sweetalert2';

	export default {
		name: "VersionRelease",

		props: ['userId','userRoleLevel','userName'],
		components: {FormModal,FeedbackModal,VersionItems,loader,navbarRight},

		data() {
			return {
				data: {},
				items: [],
				selectedVersion: {},
				errors: [],
				formAction: 'add',
				loading: false,
 				currentPage: 0,
 				itemsPerPage: 10,
				feedbackId: 0 //for feedback deletion
			}
		},
		created() {
			this.fetchList();
		},
		methods: {
			fetchList() {
				axios.get(`/version-release/all`)
				.then(response => {
					const { data, ...rest } = response.data
					this['items'] = data;

					//Set selectedVersion
					if(!_.isEmpty(this.selectedVersion)) {
						let selectedIndex = _.findIndex(this.items, ['id', this.selectedVersion.id]);
						this.viewVersion(this.items[selectedIndex >= 0 ? selectedIndex : 0]);
					}
					else this.viewVersion(this.items[0]);

				})
				.catch(error => {
					if(error.response.status === 422) {
						this.errors = error.response.data.errors;
					}
				});

				console.log('asd');
			},
			submit(data) {
				axios.post(`/version-release/store`, data)
				.then( result => {
					if (result) {
						Swal.fire({
            	    	  	title: "Version Release Saved!",
            	    	  	icon: "success",
            	    	  	confirmButtonColor: "#007bff",
            	    	  	confirmButtonText: "Close",
            	    	}).then((result) => {
            	    	    if (result.isConfirmed) window.location.reload();
            	    	});
						this.fetchList();
						this.toggleModal('form_modal', false);
					}
				}).catch(error => {
					if(error.response.status === 422) {
						this.errors = error.response.data.errors;
					}
				});
			},
			viewVersion(version) {
				this.selectedVersion = version;
			},
			isEmpty(data) {
				return _.isEmpty(data);
			},
			toggleModal(name, toggle) {
				if (toggle) {
					this.$modal.show(name);
				}
				else {
					this.errors = [];
					this.formAction = 'add';
					this.$modal.hide(name);
				}
			},
			deleteVersion(data = null) {
            if(_.isEmpty(data)) return;
            
            	Swal.fire({
            	  	title: "Delete Version " + data.version + "?",
            	  	icon: "warning",
            	  	showCancelButton: true,
            	  	confirmButtonColor: "#e24444",
            	  	cancelButtonColor: "#666666",
            	  	confirmButtonText: "Delete",
            	}).then((result) => {
            	  	if (result.isConfirmed) {
            	    	axios.delete(`/version-release/delete/${data.id}`);
            	    	Swal.fire({
            	    	  	title: "Version deleted!",
            	    	  	icon: "success",
            	    	  	confirmButtonColor: "#007bff",
            	    	  	confirmButtonText: "Close",
            	    	}).then((result) => {
            	    	    if (result.isConfirmed) window.location.reload();
            	    	});
            	  	}
            	});
        	},
			deleteFeedback(feedbackId) {
				this.formAction = 'delete';
				this.feedbackId = feedbackId;
				this.toggleModal('feedback_modal', true);
			},
			showLoader() {
				this.loading = true;
			}
		},
		computed: {
			isAdministrator() {
				return this.isAuthenticated && this.userRoleLevel > 2;
			},
			isAuthenticated() {
				return this.userId != 0 && this.userName != 'unauthenticated';
			},
			lastItem() {
				let new_features = this.selectedVersion.release_note.new ? this.selectedVersion.release_note.new.length : 0;
				let updates = this.selectedVersion.release_note.updates ? this.selectedVersion.release_note.updates.length : 0;
				let fixes = this.selectedVersion.release_note.fixes ? this.selectedVersion.release_note.fixes.length : 0;
				return new_features + updates + fixes <= 1;
			},
            logoLink(){
                return window.location.origin+'/img/lafil-logo.png';
            },
 			//Pagination
 			onFirstPage() {
 				return this.currentPage == 0;
 			},
 			onLastPage() {
 				return this.currentPage == this.totalPages - 1;
 			},
             totalPages() {
                 return Math.ceil(Object.values(this.items).length / this.itemsPerPage)
             },
             filteredQueues() {
                 var index = this.currentPage * this.itemsPerPage;
                 var queues_array = this.items.slice(index, index + this.itemsPerPage);
 
                 if(this.currentPage >= this.totalPages) this.currentPage = this.totalPages - 1;
 
                 if(this.currentPage < 0) this.currentPage = 0;
 
                 return queues_array;
             },
		}
	}
</script>
<style>
	.swal2-popup {
	    justify-items: center;
	}

	.version-link-active {
		background-color: #eef5ff;
		border-radius: 6px;
	}

	.version-link-active .navi-text {
		color: #0f5ed7;
		font-weight: 700;
	}

	.version-link-row {
		display: flex;
		align-items: center;
		width: 100%;
		white-space: nowrap;
	}

	.version-link-text {
		flex: 1 1 auto;
		min-width: 0;
		overflow: hidden;
		text-overflow: ellipsis;
	}

	.version-link-row .ml-auto {
		flex-shrink: 0;
	}
</style>