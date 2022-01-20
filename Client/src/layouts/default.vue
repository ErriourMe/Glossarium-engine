<template>
  <div class="row mx-0 default-layout">
    <div class="col-12 mb-3 mb-lg-0 pl-lg-0 col-lg-4 col-xl-3 col-uhd-2">
      <div class="default-layout__box">
        <div class="default-layout__box-content">
          <Header />
          <GlossariumNav />
        </div>
      </div>
    </div>
    <div class="col-12 mb-3 mb-lg-0 pr-lg-0 col-lg-8 col-xl-9 col-uhd-10">
      <div class="default-layout__box default-layout__box--page">
        <div class="default-layout__box-content">
          <Nuxt />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  components: {
    Header: () => import('~/components/Header'),
    GlossariumNav: () => import('~/components/Glossarium/GlossariumNav'),
  },
}
</script>

<style lang="scss">
.default-layout {
  &__box {
    height: 100vh;
    box-shadow: -6px 0 20px rgba(0, 0, 0, 0.07);

    &-content {
      z-index: 2;
      background: #fff;
      position: relative;
      height: 100vh;
      box-shadow: -6px 0 20px rgba(0, 0, 0, 0.04);
      padding: 20px;
    }

    &--page {
      position: relative;

      &:before {
        display: block;
        position: absolute;
        content: '';
        width: 10px;
        height: calc(100vh - 10px);
        top: 5px;
        left: -6px;
        z-index: 0;
        background: #ffffff;
        box-shadow: 0px 0 5px rgba(0, 0, 0, 0.02);
      }

      &:after {
        display: block;
        position: absolute;
        content: '';
        width: 15px;
        height: calc(100vh - 30px);
        top: 15px;
        left: -15px;
        z-index: -1;
        background: #ffffff;
        box-shadow: 0px 0 5px rgba(0, 0, 0, 0.1);
      }
    }
  }
}
</style>
