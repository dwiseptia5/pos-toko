let cart = [];

let selectedPaymentMethod = "Tunai";

const searchInput =
    document.getElementById("searchProduct");

const productsData = products;


/* SEARCH */

searchInput.addEventListener(
    "input",
    searchProduct
);

function searchProduct() {

    const keyword =
        searchInput.value
            .toLowerCase()
            .trim();

    const resultBox =
        document.getElementById(
            "productResults"
        );

    if (!keyword) {

        resultBox.style.display = "none";

        return;
    }

    const results =
        productsData.filter(product => {

            const name =
                String(product.name ?? "")
                    .toLowerCase();

            const code =
                String(product.sku ?? "")
                    .toLowerCase();

            const barcode =
                String(product.barcode ?? "")
                    .toLowerCase();

            return (
                name.includes(keyword) ||
                code.includes(keyword) ||
                barcode.includes(keyword)
            );

        });

    if (results.length === 0) {

        resultBox.innerHTML = `
            <div class="product-item">
                Barang tidak ditemukan
            </div>
        `;

    } else {

        resultBox.innerHTML =
            results.map(product => `

                <div
                    class="product-item"
                    onclick="addToCart(${product.id})">

                    <div>

                        <div class="product-name">
                            ${escapeHtml(product.name)}
                        </div>

                        <div class="product-code">
                            ${escapeHtml(product.sku ?? "-")}
                        </div>

                    </div>

                    <div class="product-price">
                        ${formatRupiah(product.price)}
                    </div>

                </div>

            `).join("");

    }

    resultBox.style.display = "block";
}


/* ESCAPE HTML */

function escapeHtml(value) {

    return String(value)
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");

}


/* RUPIAH */

function formatRupiah(value) {

    return new Intl.NumberFormat(
        "id-ID",
        {
            style: "currency",
            currency: "IDR",
            maximumFractionDigits: 0
        }
    ).format(Number(value) || 0);

}


/* ADD CART */

function addToCart(productId) {

    const product =
        productsData.find(
            product =>
                Number(product.id) ===
                Number(productId)
        );

    if (!product) {

        alert("Produk tidak ditemukan.");

        return;
    }

    const existing =
        cart.find(
            item =>
                Number(item.id) ===
                Number(productId)
        );

    if (existing) {

        existing.qty++;

    } else {

        cart.push({

            id: product.id,

            name: product.name,

            sku: product.sku,

            price: Number(product.price),

            qty: 1

        });

    }

    searchInput.value = "";

    document.getElementById(
        "productResults"
    ).style.display = "none";

    renderCart();

}


/* CART */

function renderCart() {

    const body =
        document.getElementById(
            "cartBody"
        );

    if (cart.length === 0) {

        body.innerHTML = `

            <tr>

                <td
                    colspan="6"
                    class="empty-cart">

                    Keranjang masih kosong.

                    <br>

                    Silakan cari atau scan barang.

                </td>

            </tr>

        `;

        calculateTotal();

        return;
    }

    body.innerHTML =
        cart.map(
            (item, index) => `

                <tr>

                    <td>
                        ${index + 1}
                    </td>

                    <td>

                        <strong>
                            ${escapeHtml(item.name)}
                        </strong>

                        <div class="product-code">
                            ${escapeHtml(item.sku ?? "-")}
                        </div>

                    </td>

                    <td>
                        ${formatRupiah(item.price)}
                    </td>

                    <td>

                        <div class="qty-control">

                            <button
                                type="button"
                                onclick="changeQty(${item.id}, -1)">
                                −
                            </button>

                            <input
                                type="number"
                                value="${item.qty}"
                                min="1"
                                onchange="updateQty(${item.id}, this.value)"
                            >

                            <button
                                type="button"
                                onclick="changeQty(${item.id}, 1)">
                                +
                            </button>

                        </div>

                    </td>

                    <td class="text-right">

                        <strong>
                            ${formatRupiah(
                                item.price *
                                item.qty
                            )}
                        </strong>

                    </td>

                    <td class="text-center">

                        <button
                            type="button"
                            class="remove-btn"
                            onclick="removeItem(${item.id})">
                            ×
                        </button>

                    </td>

                </tr>

            `
        ).join("");

    calculateTotal();

}


/* QTY */

function changeQty(id, amount) {

    const item =
        cart.find(
            item =>
                Number(item.id) ===
                Number(id)
        );

    if (!item) return;

    item.qty += amount;

    if (item.qty <= 0) {

        removeItem(id);

        return;
    }

    renderCart();

}


function updateQty(id, qty) {

    const item =
        cart.find(
            item =>
                Number(item.id) ===
                Number(id)
        );

    if (!item) return;

    item.qty =
        Math.max(
            1,
            parseInt(qty) || 1
        );

    renderCart();

}


/* REMOVE */

function removeItem(id) {

    cart =
        cart.filter(
            item =>
                Number(item.id) !==
                Number(id)
        );

    renderCart();

}


/* TOTAL */

function calculateTotal() {

    let totalQty = 0;

    let subtotal = 0;

    cart.forEach(item => {

        totalQty += Number(item.qty);

        subtotal +=
            Number(item.price) *
            Number(item.qty);

    });

    const discountPercent =
        parseFloat(
            document.getElementById(
                "discountPercent"
            ).value
        ) || 0;

    const discountAmount =
        parseFloat(
            document.getElementById(
                "discountAmount"
            ).value
        ) || 0;

    const tax =
        parseFloat(
            document.getElementById(
                "tax"
            ).value
        ) || 0;

    const otherFee =
        parseFloat(
            document.getElementById(
                "otherFee"
            ).value
        ) || 0;

    const discount =
        subtotal *
        discountPercent /
        100 +
        discountAmount;

    const grandTotal =
        Math.max(
            0,
            subtotal -
            discount +
            tax +
            otherFee
        );

    document.getElementById(
        "totalQty"
    ).innerText = totalQty;

    document.getElementById(
        "itemCount"
    ).innerText =
        totalQty + " Item";

    document.getElementById(
        "subtotal"
    ).innerText =
        formatRupiah(subtotal);

    document.getElementById(
        "grandTotal"
    ).innerText =
        formatRupiah(grandTotal);

    calculateChange();

}


/* GRAND TOTAL */

function getGrandTotal() {

    let subtotal = 0;

    cart.forEach(item => {

        subtotal +=
            Number(item.price) *
            Number(item.qty);

    });

    const discountPercent =
        parseFloat(
            document.getElementById(
                "discountPercent"
            ).value
        ) || 0;

    const discountAmount =
        parseFloat(
            document.getElementById(
                "discountAmount"
            ).value
        ) || 0;

    const tax =
        parseFloat(
            document.getElementById(
                "tax"
            ).value
        ) || 0;

    const otherFee =
        parseFloat(
            document.getElementById(
                "otherFee"
            ).value
        ) || 0;

    const discount =
        subtotal *
        discountPercent /
        100 +
        discountAmount;

    return Math.max(
        0,
        subtotal -
        discount +
        tax +
        otherFee
    );

}


/* CHANGE */

function calculateChange() {

    const total =
        getGrandTotal();

    const payment =
        parseFloat(
            document.getElementById(
                "payment"
            ).value
        ) || 0;

    const change =
        payment - total;

    const box =
        document.getElementById(
            "changeBox"
        );

    const value =
        document.getElementById(
            "change"
        );

    const label =
        document.querySelector(
            ".change-label"
        );

    if (change >= 0) {

        box.classList.remove(
            "short-payment"
        );

        label.innerText =
            "KEMBALIAN";

        value.innerText =
            formatRupiah(change);

    } else {

        box.classList.add(
            "short-payment"
        );

        label.innerText =
            "UANG KURANG";

        value.innerText =
            formatRupiah(
                Math.abs(change)
            );

    }

}


/* PAYMENT METHOD */

function selectPayment(button, method) {

    document
        .querySelectorAll(
            ".payment-method button"
        )
        .forEach(btn => {

            btn.classList.remove(
                "active"
            );

        });

    button.classList.add("active");

    selectedPaymentMethod = method;

}


/* QUICK PAYMENT */

function quickPayment(amount) {

    document.getElementById(
        "payment"
    ).value = amount;

    calculateChange();

}


function exactPayment() {

    document.getElementById(
        "payment"
    ).value =
        getGrandTotal();

    calculateChange();

}


/* HOLD */

function holdTransaction() {

    if (cart.length === 0) {

        alert(
            "Tidak ada transaksi untuk ditahan."
        );

        return;
    }

    alert(
        "Transaksi berhasil ditahan."
    );

}


/* CANCEL */

function cancelTransaction() {

    if (
        !confirm(
            "Batalkan transaksi ini?"
        )
    ) {
        return;
    }

    cart = [];

    document.getElementById(
        "payment"
    ).value = "";

    document.getElementById(
        "discountPercent"
    ).value = 0;

    document.getElementById(
        "discountAmount"
    ).value = 0;

    document.getElementById(
        "tax"
    ).value = 0;

    document.getElementById(
        "otherFee"
    ).value = 0;

    renderCart();

}


/* PAYMENT */

function processPayment() {

    if (cart.length === 0) {

        alert(
            "Keranjang masih kosong."
        );

        return;
    }

    const total =
        getGrandTotal();

    const payment =
        parseFloat(
            document.getElementById(
                "payment"
            ).value
        ) || 0;

    if (payment < total) {

        alert(
            "Uang pembayaran masih kurang."
        );

        return;
    }

    const form =
        document.createElement(
            "form"
        );

    form.method = "POST";

    form.action =
        "/kasir/transaksi";

    addInput(
        form,
        "_token",
        document.querySelector(
            'meta[name="csrf-token"]'
        )?.content ||
        document
            .querySelector(
                'input[name="_token"]'
            )?.value
    );

    cart.forEach(
        (item, index) => {

            addInput(
                form,
                `items[${index}][product_id]`,
                item.id
            );

            addInput(
                form,
                `items[${index}][qty]`,
                item.qty
            );

        }
    );

    const subtotal =
        cart.reduce(
            (sum, item) =>
                sum +
                Number(item.price) *
                Number(item.qty),
            0
        );

    const discountPercent =
        parseFloat(
            document.getElementById(
                "discountPercent"
            ).value
        ) || 0;

    const discountAmount =
        parseFloat(
            document.getElementById(
                "discountAmount"
            ).value
        ) || 0;

    const discount =
        subtotal *
        discountPercent /
        100 +
        discountAmount;

    addInput(
        form,
        "discount",
        discount
    );

    addInput(
        form,
        "tax",
        document.getElementById(
            "tax"
        ).value
    );

    addInput(
        form,
        "other_fee",
        document.getElementById(
            "otherFee"
        ).value
    );

    addInput(
        form,
        "paid_amount",
        payment
    );

    addInput(
        form,
        "payment_method",
        selectedPaymentMethod
    );

    document.body.appendChild(form);

    form.submit();

}


/* INPUT */

function addInput(
    form,
    name,
    value
) {

    const input =
        document.createElement(
            "input"
        );

    input.type = "hidden";

    input.name = name;

    input.value = value ?? "";

    form.appendChild(input);

}


/* DATE */

document.getElementById(
    "currentDate"
).innerText =
    new Date().toLocaleString(
        "id-ID"
    );


/* KEYBOARD */

document.addEventListener(
    "keydown",
    function(event) {

        if (event.key === "F2") {

            event.preventDefault();

            searchInput.focus();

        }

        if (event.key === "F4") {

            event.preventDefault();

            document.getElementById(
                "payment"
            ).focus();

        }

        if (event.key === "Escape") {

            cancelTransaction();

        }

    }
);


/* CLICK OUTSIDE */

document.addEventListener(
    "click",
    function(event) {

        const resultBox =
            document.getElementById(
                "productResults"
            );

        const searchArea =
            document.querySelector(
                ".search-input"
            );

        if (
            !searchArea.contains(
                event.target
            )
        ) {

            resultBox.style.display =
                "none";

        }

    }
);


/* INITIAL */

renderCart();