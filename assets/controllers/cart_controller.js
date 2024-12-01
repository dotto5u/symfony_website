import { Controller } from '@hotwired/stimulus';
import { getComponent } from '@symfony/ux-live-component';

export default class extends Controller {
  async initialize() {
    this.component = await getComponent(this.element);
  }

  updateProductQuantity(event) {
    const select = event.target;
    const productId = select.id;
    const selectedQuantity = select.value;

    this.component.emit('updateProductQuantity', {
      id: productId,
      quantity: selectedQuantity
    });
  }
}
