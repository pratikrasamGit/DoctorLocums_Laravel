<template>
  <div>
    <div 
      class="alert alert-danger form-error"
      v-if="errors || error"
    >
      <ul>
          <li 
            v-for="(error, index) in errors" 
            :key="index"
          > 
            {{ error }} 
          </li>
          <li v-if="error">
            {{error}}
          </li>
      </ul>
    </div>
  </div>
</template>
<script>
export default {
  name: 'VErrors',
  props: {
    error: {
      type: String,
      default: null,
    },
    errors: {
      type: Array,
      default: null,
    }
  }
}
</script>