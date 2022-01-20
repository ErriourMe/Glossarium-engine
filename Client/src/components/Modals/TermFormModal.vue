<template>
  <b-modal
    :id="id"
    title-class="text-left"
    centered
    :title="
      Object.keys(form || {}).length || type !== 'create'
        ? `Редактировать термин`
        : `Создать термин`
    "
    hide-footer
    size="xl"
  >
    <div class="row d-flex justify-content-center">
      <div class="col-12">
        <form @submit.prevent="store">
          <InputField
            id="term_title"
            v-model="localForm.title"
            title="Название термина"
            :validation="localValidation.title"
            @input="localValidation.title = []"
          />

          <TextEditorField
            id="term_description"
            v-model="localForm.description"
            title="Определение"
            :validation="localValidation.description"
            @input="localValidation.description = []"
          />

          <div class="form-group">
            <label>Связанные термины</label>
            <multiselect
              v-model="selected.similar_terms"
              :options="terms"
              track-by="id"
              label="title"
              multiple
              placeholder="Выберите термины"
            />
          </div>

          <div class="d-flex justify-content-center w-100 mt-4">
            <button
              type="submit"
              class="btn btn-primary w-35 justify-content-center"
            >
              {{
                Object.keys(form || {}).length || type !== 'create'
                  ? `Обновить`
                  : `Создать`
              }}
            </button>
          </div>
        </form>
      </div>
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
    form: {
      type: Object,
      required: false,
      default: () => {},
    },
    type: {
      type: String,
      required: false,
      default: 'create',
    },
    validation: {
      type: Object,
      required: false,
      default: () => {
        return {}
      },
    },

    terms: {
      type: Array,
      required: false,
      default: () => [],
    },
  },
  data() {
    return {
      localForm: { ...this.form },
      localValidation: { ...this.validation },
      selected: {
        similar_terms: [],
      },
    }
  },
  watch: {
    form: {
      handler(val) {
        this.localForm = { ...val }
        this.selected.similar_terms = [
          ...(val?.similar_terms || []).map((el) => el.term),
        ]
      },
      deep: true,
    },
    validation(val) {
      this.localValidation = { ...val }
    },
    'selected.similar_terms'(val) {
      this.localForm.similar_terms = val.map((el) => el.id)
    },
  },
  methods: {
    store() {
      this.$emit(
        Object.keys(this?.form || {}).length || this.type !== 'create'
          ? 'update'
          : 'create',
        this.localForm
      )
    },
  },
}
</script>
