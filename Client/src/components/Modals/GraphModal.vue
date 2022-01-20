<template>
  <b-modal :id="id" centered size="xxl" hide-header hide-footer>
    <div class="d-flex flex-column align-items-center text-center">
      <img src="/static/g.png" alt="Граф терминов" class="g__img" />
    </div>
  </b-modal>
</template>

<script>
export default {
  props: {
    id: {
      type: String,
      required: true,
    },
  },
}
</script>

<style lang="scss">
.g__img {
  width: 100%;
  height: 100%;
  object-fit: contain;
}
</style>
