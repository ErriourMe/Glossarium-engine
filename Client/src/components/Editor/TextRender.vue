<template>
  <div>
    <template v-if="isJson(content)">
      <div
        v-for="(item, i) in JSON.parse(content).blocks"
        :key="`editor-content-${i}`"
      >
        <p v-if="item.type === 'paragraph'" v-html="item.data.text"></p>
        <template v-if="item.type === 'header'">
          <h2 v-if="item.data.level === 1">{{ item.data.text }}</h2>
          <h2 v-if="item.data.level === 2">{{ item.data.text }}</h2>
          <h3 v-if="item.data.level === 3">{{ item.data.text }}</h3>
          <h4 v-if="item.data.level === 4">{{ item.data.text }}</h4>
          <h5 v-if="item.data.level === 5">{{ item.data.text }}</h5>
          <h6 v-if="item.data.level === 6">{{ item.data.text }}</h6>
        </template>
        <ul v-if="item.type === 'list'" :class="`list-${item.data.style}`">
          <li
            v-for="(listItem, j) in item.data.items"
            :key="`editor-content-list-${j}`"
            v-html="listItem"
          ></li>
        </ul>
        <div v-if="item.type === 'delimiter'" class="delimiter"></div>
        <div v-if="item.type === 'warning'" class="alert alert-danger">
          <h5 class="alert-heading">{{ item.data.title }}</h5>
          <p class="mb-0">{{ item.data.message }}</p>
        </div>
        <div v-if="item.type === 'table'">
          <table class="w-100">
            <tbody>
              <tr
                v-for="(line, j) in item.data.content"
                :key="`editor-content-table-${j}`"
              >
                <td
                  v-for="(cell, k) in line"
                  :key="`editor-content-table-${k}`"
                >
                  {{ cell }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- <vue-code-highlight v-if="item.type === 'code'">{{ item.data.code }}</vue-code-highlight> -->
        <div v-if="item.type === 'image'" class="row">
          <div :class="setImageClasses(item.data)">
            <img
              class="img-fluid image-thread"
              :src="item.data.file.url"
              :alt="item.data.caption"
            />
            <div
              v-if="item.data.caption"
              class="text-center image-caption f-14 text-gray mt-2"
            >
              {{ item.data.caption }}
            </div>
          </div>
        </div>
        <a
          v-if="item.type === 'link'"
          class="og-link"
          target="_blank"
          rel="nofollow noindex noreferrer"
          :href="item.data.link || ''"
        >
          <template v-if="item.data.meta">
            <div
              v-if="item.data.meta.image"
              class="link-image"
              :style="`background-image: url(${
                item.data.meta.image.url || ''
              });`"
            ></div>
            <div v-if="item.data.meta.title" class="link-title">
              {{ item.data.meta.title || '' }}
            </div>
            <p v-if="item.data.meta.description" class="link-description">
              {{ item.data.meta.description || '' }}
            </p>
          </template>
          <span v-if="item.data.link" class="link-anchor">{{
            extractUrl(item.data.link || '')
          }}</span>
        </a>
      </div>
    </template>
    <div v-else>
      {{ content }}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    content: {
      type: String,
      required: true,
    },
  },
  methods: {
    setImageClasses(data) {
      let imgClass = 'col-12 text-center'
      if (data.stretched) imgClass += ' image-stretched'
      if (data.withBorder) imgClass += ' image-bordered'
      if (data.withBackground) imgClass += ' image-backgrounded'

      return imgClass
    },
    extractUrl(link) {
      return new URL(link).hostname
    },
    isJson(str) {
      try {
        JSON.parse(str)
      } catch (e) {
        return false
      }
      return true
    },
  },
}
</script>
