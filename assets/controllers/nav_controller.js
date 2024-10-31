import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ['navLinks', 'navMenuIcon', 'navCloseIcon'];

  initialize() {
    this.isOpen = false;
  }

  toggle() {
    this.isOpen = !this.isOpen;

    this.navMenuIconTarget.classList.toggle('hidden', this.isOpen);
    this.navCloseIconTarget.classList.toggle('hidden', !this.isOpen);

    if (this.isOpen) {
      this.navLinksTarget.classList.remove('translate-y-[-125%]', 'duration-0');
      this.navLinksTarget.classList.add('duration-300');
    } else {
      this.navLinksTarget.classList.add('translate-y-[-125%]');
      this.navLinksTarget.addEventListener('transitionend', this.removeAnimationDuration.bind(this), { once: true });
    }
  }

  removeAnimationDuration() {
    this.navLinksTarget.classList.remove('duration-300');
  }
}
