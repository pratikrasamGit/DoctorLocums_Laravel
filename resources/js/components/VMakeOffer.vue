<template>
  <div id="small-dialog" class="zoom-anim-dialog mfp-hide dialog-with-tabs">
    <div class="make-offer">
      <div class="popup-tabs-container">
        <div class="popup-tab-content" id="tab">
          <v-errors :error="networkError" />
          <template v-if="jobs">
          <div class="welcome-text">
            <h3>Make an offer to {{ userName }}</h3>
          </div>
          <div class="input-with-icon-left">
              <i class="icon-feather-user"></i>
              <input
                type="text"
                class="input-text with-border"
                disabled="disabled"
                name="name"
                id="name"
                :value="currentUserName"
              />
            </div>
            <div class="input-with-icon-left margin-bottom-25">
              <i class="icon-feather-link"></i>
              <select @change="onChange($event)" class="make-offer_select" name="make_offer_jobs" id="make_offer_jobs">
                <option value="">Select Job/Assignment</option>
                <option
                    v-for="(name, id) in jobs"
                    :value="id"
                    :key="id"
                >{{name}}</option>
            </select>
            </div>
            <v-loading v-if="loading > 0" />
            <template v-if="msg === 'success'">
              <p>
                <strong>Your offer has successfully been submitted for this assignment.</strong>
              </p>
            </template>
            <template v-if="mode === 'view'">
            <div class="job-message margin-bottom-25">
              <p>
                Hello
                <strong>{{ userName }}</strong>,
              </p>
              <p>
                {{ currentUserName }} would like to book you for the assignment below.
                <br />Facility Name: {{this.facilityName}}
                <br />Location: {{this.location}}
                <br />Specialty: {{this.specialty}}
                <br />Start Date: {{this.jobDetail.startdate}}
                <br />Duration: {{this.jobDetail.duration}}
                <br />Shift: {{this.jobDetail.shift}}
                <br />Work Days: {{this.jobDetail.workdays}}
              </p>
            </div>
            <div class="job-term margin-bottom-25">
              <p>
                <strong>TERMS ACKNOWLEDGMENT</strong>
              </p>
              <p>By clicking on the “Make an Offer” your facility agrees to pay the hourly bill rate reflected on the nurse’s profile page per the terms established in the Nurseify vendor agreement</p>
            </div>
            <div class="job-next margin-bottom-25">
              <p>
                <strong>NEXT STEPS</strong>
              </p>
              <ul>
                <li>
                  <strong>{{ userName }}</strong> will have 48 hours to accept your booking request
                </li>
                <li>You will receive an email notice after the nurse accepts or rejects the request</li>
                <li>Assuming the nurse accepts, a Nurseify Consultant will contact you to coordinate onboarding logistics</li>
                <li>If the nurse rejects, we will provide additional nurses that may meet your need</li>
                <li>
                  Contact us anytime at
                  <a href="mailto:info@nurseify.app">info@nurseify.app</a>
                </li>
              </ul>
            </div>
            <button
              class="button margin-top-35 full-width button-sliding-icon ripple-effect"
              type="submit"
              v-if="jobId"
              @click="makeOffer">
              Make an Offer
              <i class="icon-material-outline-arrow-right-alt"></i>
            </button>
            </template>
          </template>
          <template v-else>
            <div class="job-message margin-bottom-25">
              <p>
                <strong>
                {{ userName }} has already received offers for the assignments listed within your facility and or facilities. 
                Please allow 48 hours for {{ userName }} to accept or reject any offers submitted.
                </strong>
              </p>
            </div>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
    data() {
        return {
            loading: 0,
            mode: "hide",
            jobs:{},
            msg:"",
            jobId: "",
            networkError: null,
            errors: {},
            jobDetail: {},
            facilityName: "",
            location: "",
            specialty: ""
        };
    },
  props: {
    currentUserName: {
      type: String,
      requried: true,
    },
    userName: {
      type: String,
      requried: true,
    },
    nurse: {
      type: String,
      requried: true,
    }
  },
  async mounted() {
    return this.fetchData();
  },
  methods: {
    async fetchData() {
      this.loading++;
      try {
        const { data } = await axios.get('/api/'+ this.nurse +'/jobs');
        this.jobs = data;
        return data;
      } catch (err) {
        console.log(err);
        const errorMessages = err?.response?.data?.errors;
        if(errorMessages){
          this.errors = errorMessages
        } else {
          this.networkError = err.message;
        }
        throw err;
      } finally {
        this.loading--;
      }
    },
    async onChange(event) {
          this.jobId = event.target.value;
          if (this.jobId) {
            this.loading++;
            try {
              const { data } = await axios.get('/api/job/'+ this.jobId +'/facility');
              this.facilityName = data.name;
              this.location = data.location;
              this.specialty = data.specialty;
              this.jobDetail = data.jobDetail;
              this.mode = "view";
              this.msg = "";
            } catch (err) {
              console.log(err);
              const errorMessages = err?.response?.data?.errors;
              if(errorMessages){
                this.errors = errorMessages
              } else {
                this.networkError = err.message;
              }
              throw err;
            } finally {
              this.loading--;
            }
          }else{
            this.mode = "hide";
            this.jobId = "";
            this.facilityName = "";
            this.location = "";
            this.specialty = "";
            this.jobDetail = {};
            this.msg = "";
          }
    },
    async makeOffer() {  
      if (this.jobId) {
        try {
          this.loading++;
          const { data } = await axios.get('/api/job/'+ this.jobId +'/invite/'+this.nurse);
          this.mode = "hide";
          this.jobId = "";
          this.facilityName = "";
          this.location = "";
          this.specialty = "";
          this.jobDetail = {};
          this.msg = "success";          
        } catch (err) {
          console.log(err);
          const errorMessages = err?.response?.data?.errors;
          if(errorMessages){
            this.errors = errorMessages
          } else {
            this.networkError = err.message;
          }
          throw err;
        } finally {
          this.loading--;
        }  
        return this.fetchData();
      }else{
        this.mode = "hide";
        this.jobId = "";
        this.facilityName = "";
        this.location = "";
        this.specialty = "";
        this.jobDetail = {};
        this.msg = "";
      }
    } 
  },
  computed: {

  },
  created() {

  },
};
</script>