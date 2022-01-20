<template>
  <div class="term-page">
    <div class="d-flex align-items-center justify-content-between w-100 mb-3">
      <h1>{{ term.title }}</h1>
      <button
        type="button"
        class="btn btn-gray btn-sm"
        @click="$bvModal.show('editTerm')"
      >
        Редактировать
      </button>
    </div>

    <TextRender :content="term.description" />

    <div
      v-if="Object.keys(term.similar_terms).length"
      class="mt-5 pt-5 term-page__similar"
    >
      <h4>Связанные термины:</h4>
      <div>
        <NuxtLink
          v-for="(st, i) in term.similar_terms"
          :key="`st-${st.id}-${i}`"
          class="btn btn-sm btn-primary mr-2"
          :to="`/term/${st.term.id}`"
        >
          {{ st.term.title }}
        </NuxtLink>
      </div>
    </div>

    <TermFormModal
      id="editTerm"
      :validation="validation.term"
      :form="term"
      :terms="terms"
      @update="updateTerm"
    />
  </div>
</template>

<script>
export default {
  components: {
    TextRender: () => import('~/components/Editor/TextRender'),
    TermFormModal: () => import('~/components/Modals/TermFormModal'),
  },
  async asyncData({ $axios, params, app }) {
    const term = await $axios.get(
      `${process.env.API_ROUTE}/${process.env.API_VERSION}/terms/${params.id}`
    )

    return {
      term: term.data.data.term,
    }
  },
  data() {
    return {
      validation: {
        term: {},
      },
      terms: [],
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
        })
    },
    updateTerm(data) {
      this.$axios
        .put(
          `${process.env.API_ROUTE}/${process.env.API_VERSION}/terms/${this.term.id}`,
          data
        )
        .then(() => {
          this.$bvModal.hide('editTerm')
          this.$router.app.refresh()
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
.term-page {
  ul,
  li {
    list-style-type: disc;
    margin-left: 1rem;
  }

  ul {
    margin-bottom: 1rem;
  }
}
</style>
