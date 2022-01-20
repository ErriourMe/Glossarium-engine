<template>
  <div class="form-group">
    <label>{{ title }}</label>
    <TextEditor
      :id="id"
      :save-onchange="true"
      :placeholder="placeholder"
      :content="parsedValue"
      :class="`form-control h-100 ${validation.length ? `is-invalid` : ``}`"
      @save="saveData"
    />
    <div class="invalid-feedback">
      <div v-for="(error, i) in validation" :key="`errors-${i}`">
        {{ error }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  components: {
    TextEditor: () => import('~/components/Editor/TextEditor'),
  },
  props: {
    id: {
      type: String,
      required: true,
    },
    title: {
      type: String,
      required: false,
      default: '',
    },
    type: {
      type: String,
      required: false,
      default: 'text',
    },
    placeholder: {
      type: String,
      required: false,
      default: 'Начните писать текст...',
    },
    validation: {
      type: Array,
      required: false,
      default: () => [],
    },

    /// ///////// ///
    value: {
      type: String,
      required: false,
      default: '',
    },
  },
  computed: {
    model: {
      get() {
        return this.value
      },
      set(val) {
        this.$emit('input', JSON.stringify(val))
      },
    },
    parsedValue() {
      return this.value
    },
  },
  methods: {
    saveData(content) {
      this.$emit('input', JSON.stringify(content))
    },
  },
}
</script>
