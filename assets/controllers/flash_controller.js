import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    close() {
        this.element.classList.remove('animate-slide-in');
        this.element.classList.add('animate-slide-out');
        this.element.addEventListener('transitionend', this.removeFlash.bind(this), { once: true });
    }

    removeFlash() {
        this.element.remove();
    }
}
