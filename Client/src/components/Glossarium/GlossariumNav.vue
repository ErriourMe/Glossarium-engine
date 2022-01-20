<template>
  <div class="glossarium-nav">
    <div class="glossarium-nav__wrapper">
      <div v-if="loaded" class="glossarium-nav__elements">
        <div
          v-for="(term, i) in terms"
          :key="`g-term-${i}`"
          class="glossarium-nav__term"
        >
          <NuxtLink :to="`/term/${term.id}`">{{ term.title }}</NuxtLink>
        </div>
      </div>
      <div v-else class="glossarium-nav__loading">
        <b-spinner label="Загрузка" variant="gray" />
      </div>
    </div>

    <TermFormModal
      id="createTerm"
      :validation="validation.term"
      :form="{}"
      :terms="terms"
      @create="createTerm"
    />
  </div>
</template>

<script>
export default {
  components: {
    TermFormModal: () => import('~/components/Modals/TermFormModal'),
  },
  data() {
    return {
      terms: [],
      loaded: false,
      validation: {
        term: {},
      },
    }
  },
  mounted() {
    this.getTerms()
  },
  methods: {
    getTerms() {
      this.$axios
        .get(
          `${process.env.API_ROUTE}/${process.env.API_VERSION}/terms?order[title]=asc`
        )
        .then((res) => {
          this.terms = res.data.data.terms
          this.loaded = true
        })
    },
    createTerm(data) {
      this.$axios
        .post(`${process.env.API_ROUTE}/${process.env.API_VERSION}/terms`, data)
        .then((res) => {
          this.getTerms()
          this.$bvModal.hide('createTerm')
          this.$router.push(`/term/${res.data.data.term_id}`)
        })
        .catch((error) => {
          if (typeof error.response.data.validation === 'object') {
            this.validation.term = error.response.data.validation
          }
        })
    },
  },
}
</script>

<style lang="scss">
.glossarium-nav {
  padding-top: 15px;
  margin-top: 15px;
  border-top: 1px solid #f5f5f5;

  &__wrapper {
    height: calc(100vh - 68px - 40px - 80px);
    @media (max-width: 2500px) {
      height: calc(100vh - 68px - 40px - 120px);
    }
    overflow-y: hidden;
    padding-right: 5px;

    &:hover {
      overflow-y: scroll;
      padding-right: 0px;
    }
  }

  &__loading {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
  }
}
</style>
