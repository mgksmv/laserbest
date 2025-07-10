import { ref } from 'vue';

export function useModal() {
  const isOpened = ref(false);

  function open() {
    isOpened.value = true;
  }

  function close() {
    isOpened.value = false;
  }

  return { isOpened, open, close };
}
