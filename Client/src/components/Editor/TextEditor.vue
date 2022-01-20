<template>
  <div>
    <client-only>
      <div :id="id" class="editor-fields"></div>
      <template #placeholder>
        <div class="editor-loading">
          <b-spinner variant="gray" />
        </div>
      </template>
    </client-only>
  </div>
</template>

<script>
import { translation } from '~/lang/ru/editor'
let EditorJS = null
let DragDrop = null
let ImageTool = null
let HeaderTool = null
let ListTool = null
let MarkerTool = null
let LinkTool = null
let DelimiterTool = null
// let WarningTool = null
let ParagraphTool = null
let Hyperlink = null
let Table = null
let Undo = null
if (process.client) {
  EditorJS = require('@editorjs/editorjs')
  DragDrop = require('editorjs-drag-drop')
  Undo = require('editorjs-undo')
  ImageTool = require('@editorjs/image')
  HeaderTool = require('@editorjs/header')
  ListTool = require('@editorjs/list')
  MarkerTool = require('@editorjs/marker')
  LinkTool = require('@editorjs/link')
  DelimiterTool = require('@editorjs/delimiter')
  // WarningTool = require('@editorjs/warning')
  ParagraphTool = require('@editorjs/paragraph')
  Hyperlink = require('editorjs-hyperlink')
  Table = require('editorjs-table')
}
export default {
  props: {
    id: {
      type: String,
      required: true,
    },
    content: {
      type: String,
      required: false,
      default: '',
    },
    handlerByFile: {
      type: String,
      required: false,
      default: `${process.env.API_DOMAIN}/api/v1/handler/image/page`,
    },
    handlerByURL: {
      type: String,
      required: false,
      default: '',
    },
    placeholder: {
      type: String,
      required: false,
      default: 'New paragraph',
    },
  },
  data() {
    return {
      editor: null,
    }
  },
  mounted() {
    if (EditorJS) {
      this.editor = new EditorJS({
        logLevel: 'ERROR',
        i18n: translation,
        holder: this.id,
        onReady: () => {
          const editor = this.editor
          // eslint-disable-next-line no-new
          new Undo({ editor })
          // eslint-disable-next-line no-new
          new DragDrop(editor)
        },
        onChange: () => {
          this.saveEditor()
        },
        data: this.content
          ? this.isJson(this.content)
            ? JSON.parse(this.content)
            : { blocks: [{ type: 'paragraph', data: { text: this.content } }] }
          : {},
        tools: {
          table: {
            class: Table,
            inlineToolbar: true,
            config: {
              rows: 2,
              cols: 3,
            },
          },
          hyperlink: {
            class: Hyperlink,
            config: {
              shortcut: 'CMD+L',
              target: '_blank',
              rel: 'nofollow',
              availableTargets: ['_blank', '_self'],
              availableRels: [],
              validate: false,
            },
          },
          header: {
            class: HeaderTool,
            config: {
              placeholder: 'Введите подзаголовок',
              levels: [2, 3, 4],
              defaultLevel: 3,
            },
          },
          list: {
            class: ListTool,
          },
          marker: {
            class: MarkerTool,
          },
          link: {
            class: LinkTool,
          },
          delimiter: {
            class: DelimiterTool,
          },
          paragraph: {
            class: ParagraphTool,
          },
          image: {
            class: ImageTool,
            config: {
              uploader: {
                /**
                 * Upload file to the server and return an uploaded image data
                 * @param {File} file - file selected from the device or pasted by drag-n-drop
                 * @return {Promise.<{success, file: {url}}>}
                 */
                uploadByFile(file) {
                  try {
                    // eslint-disable-next-line no-undef
                    return globalThis.$nuxt.$axios
                      .post(
                        `${process.env.API_DOMAIN}/${process.env.API_VERSION}/image/file/post`,
                        file
                      )
                      .then(() => {
                        return {
                          success: 1,
                          file: {
                            url: null,
                          },
                        }
                      })
                      .catch(() => {
                        const template = require('~/templates/notifications/full')
                        // eslint-disable-next-line no-undef
                        globalThis.$nuxt.$toast.error(
                          template({
                            title: 'Ошибка загрузки изображения',
                            text: 'Не удалось загрузить изображение, попробуйте снова',
                          }),
                          {
                            duration: 10000,
                          }
                        )
                        return {
                          error: 'Broken handler',
                        }
                      })
                  } catch (e) {
                    console.log('TEST')
                    return {
                      error: e,
                    }
                  }
                },
              },
              endpoints: {
                byFile: this.handlerByFile || null,
                byUrl: this.handlerByURL || null,
              },
              field: 'image',
              types: 'image/*',
              additionalRequestHeaders: {
                authorization: `Bearer ${this.$cookies.get('token') || ''}`,
              },
            },
          },
        },
        placeholder: this.placeholder,
      })
    }
  },
  destroyed() {
    document.querySelectorAll('.ct').forEach((a) => a.remove())
  },
  methods: {
    saveEditor() {
      this.editor
        .save()
        .then((outputData) => this.$emit('save', outputData))
        // eslint-disable-next-line no-console
        .catch((err) => console.error(err))
    },
    clear() {
      if (!this.editor) {
        return
      }
      this.editor.blocks.clear()
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

<style lang="scss">
.editor-loading {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  position: absolute;
}
.codex-editor {
  &__redactor {
    padding-bottom: 100px !important;
    margin-right: 0 !important;
  }
}
.ce-block {
  &__content {
    max-width: calc(100% + 20px);
    padding: 0 12px;
    margin: 0px -5px;
  }
  &--selected {
    .ce-block__content {
      background: #eff8ff;
      border-radius: 6px;
    }
  }
}
.ce-toolbar {
  &__content {
    max-width: 100%;
  }
  &__actions {
    right: -62px !important;
  }
  &__plus {
    left: -62px !important;
    width: 24px;
    height: 24px;
    border-radius: 6px;
    background: #fff;
    border: 1px solid #f2f2f2;
    /* Fix it */
    top: -9px;
    &--active,
    &:hover {
      color: #2c3e77;
    }
    svg {
      padding: 2px;
    }
  }
  &__settings-btn {
    width: 24px;
    height: 24px;
    border-radius: 6px;
    background: #fff;
    border: 1px solid #f2f2f2;
  }
}
.ce-paragraph {
  cursor: text;
  font-size: 18px;
  padding: 0 0 1em;
  &[data-placeholder]:empty {
    height: 46.8px;
  }
  &[data-placeholder]:empty::before {
    color: #cecece;
    font-size: 14px;
  }
}
.ce-delimiter {
  &:before {
    font-size: 2.2rem;
    font-weight: bold;
  }
}
.ce-header {
  padding: 0 0 1.2em;
}
.ce-settings {
  &__plugin-zone {
    .cdx-settings-button svg {
      padding: 0px !important;
    }
  }
}
.ce-inline-tool-hyperlink {
  &--select-rel {
    display: none;
  }
  &--select-target {
    width: 100%;
  }
  &--button {
    background-color: #2c3e77;
    border-color: #2c3e77;
    transition: 0.2s ease;
  }
  &:hover,
  &:active {
    background-color: #394e92;
    border-color: #394e92;
  }
}
.ce-toolbox {
  &__button--active,
  &__button:hover {
    color: #2c3e77;
  }
}
.cdx-quote {
  border-left: 3px solid #515450;
  padding: 0;
  margin: 0 0 1rem;
  &__text {
    border: none;
    box-shadow: none;
    padding: 0 15px;
    min-height: auto;
    margin: 0;
  }
  &__caption {
    border: none;
    box-shadow: none;
    padding: 0 15px 0 43px;
    margin: 4px 0;
    font-style: italic !important;
    display: flex;
    position: relative;
    span,
    b {
      font-style: italic !important;
      font-weight: normal !important;
    }
    &:after {
      content: '—' !important;
      margin-right: 7px;
      position: absolute;
      left: 16px;
      font-style: italic;
    }
  }
}
.ct {
  z-index: 1100;
}
</style>
