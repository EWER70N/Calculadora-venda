function addProduct() {
    const productForm = document.createElement('div');
    productForm.className = 'product-form';
    productForm.innerHTML = 
       `<input type="text" name="product_name[]" placeholder="Nome do produto" required>
        <input type="number" name="product_quantity[]" placeholder="Quantidade" min="1" required>
        <input type="number" name="product_price[]" placeholder="Preço (R$)" step="0.01" min="0" required>`;
    document.getElementById('product-list').appendChild(productForm);
}


