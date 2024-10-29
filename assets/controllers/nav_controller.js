import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ['navLinks', 'menuIcon', 'closeIcon'];

  initialize() {
    this.isOpen = false
  }

  toggle() {
    this.isOpen = !this.isOpen;

    this.menuIconTarget.classList.toggle('hidden', this.isOpen);
    this.closeIconTarget.classList.toggle('hidden', !this.isOpen);
    this.navLinksTarget.classList.toggle('translate-y-[-125%]');
  }
}
