export const toastConfig = {
  duration: 10000,
  queue: true,
  position: 'bottom-left',
  action: {
    text: '×',
    onClick: (e, toastObject) => {
      toastObject.goAway(0)
    },
  },
}
