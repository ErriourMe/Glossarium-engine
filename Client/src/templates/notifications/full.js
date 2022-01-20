module.exports = (options) =>
  `
    <div class="toast-card">
      <div class="toast-content">
        <div class="toast-content__data">
            ${
              options.title
                ? `<div class="toast-content__title">${options.title}</div>`
                : ``
            }
            ${
              options.text
                ? `<div class="toast-content__message">${options.text}</div>`
                : ``
            }
        </div>
      </div>
    </div>
  `
