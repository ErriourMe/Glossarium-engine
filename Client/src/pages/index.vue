<template>
  <div class="index-page__empty">
    <div>Выберите термин для просмотра</div>
    <button class="btn btn-gray mt-5" type="button" @click="$bvModal.show('g')">
      Граф терминов
    </button>

    <GraphModal id="g" />
  </div>
</template>

<script>
export default {
  components: {
    GraphModal: () => import('~/components/Modals/GraphModal'),
  },
}
</script>

<style lang="scss">
.index-page {
  &__empty {
    width: 100%;
    height: 100%;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-size: 2rem;

    color: #bfbfbf;
    font-style: italic;
  }
}
</style>
