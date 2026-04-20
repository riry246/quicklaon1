<template>
  <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 biglogo">
    <div class="p-4 timeline-front">
      <ul class="list-unstyled timeline-widget mb-0 my-3">
        <li v-for="(stepData, index) in steps" :key="index"
          :class="{ 'timeline-widget-list': true, completed_step: step > index, hidden: step > index + 1 }">
          <div class="d-flex align-items-top">
            <div class="me-2 text-center">
              <span class="avatar avatar-rounded fs-20 avatar-rounded">{{ index + 1 }}</span>
            </div>
            <div class="d-flex flex-wrap flex-fill align-items-center justify-content-between">
              <div>
                <p class="mb-1 text-truncate timeline-widget-content text-wrap fs-18 fw-semibold">{{ stepData.title }}</p>
                <p class="mb-0 fs-12 lh-1 text-muted">{{ stepData.description }}</p>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>
  
<script>
export default {
  props: ["step"],
  computed: {
    steps() {
      return [
        { title: "Step 1", description: "Basic Information" },
        { title: "Step 2", description: "Loan Details" },
        { title: "Step 3", description: "Income" },
        { title: "Step 4", description: "Employment Details" },
        { title: "Step 5", description: "Terms & Conditions" },
        { title: "Step 6", description: "Bank Verification" },
        { title: "Step 7", description: "Expenses" },
        { title: "Step 8", description: "Success", hidden: true },
      ];
    },
  },
};
</script>
  