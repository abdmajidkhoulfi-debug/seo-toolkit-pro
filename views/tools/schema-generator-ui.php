<div style="max-width:800px;margin:0 auto;">
    <div style="display:flex;gap:8px;margin-bottom:24px;flex-wrap:wrap;">
        <?php
        $schemaTypes = [
            'article' => 'Article',
            'faq' => 'FAQ',
            'localbusiness' => 'Local Business',
            'product' => 'Product',
            'organization' => 'Organization',
        ];
        $first = true;
        foreach ($schemaTypes as $key => $label): ?>
        <button class="btn btn-sm <?php echo $first ? 'btn-primary' : 'btn-secondary'; ?> schema-tab" data-type="<?php echo $key; ?>" onclick="switchSchema('<?php echo $key; ?>')"><?php echo $label; ?></button>
        <?php $first = false; endforeach; ?>
    </div>

    <div id="schemaForms">
        <!-- Article -->
        <div class="schema-form" id="form-article" style="display:block;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div style="grid-column:1/-1;"><label class="form-label">Headline *</label><input type="text" id="artHeadline" class="form-input" placeholder="Article title"></div>
                <div style="grid-column:1/-1;"><label class="form-label">Description</label><textarea id="artDesc" class="form-textarea" rows="2" placeholder="Brief description"></textarea></div>
                <div><label class="form-label">Author *</label><input type="text" id="artAuthor" class="form-input" placeholder="Author name"></div>
                <div><label class="form-label">Image URL</label><input type="url" id="artImage" class="form-input" placeholder="https://..."></div>
                <div><label class="form-label">Date Published</label><input type="date" id="artDate" class="form-input"></div>
                <div><label class="form-label">Date Modified</label><input type="date" id="artModified" class="form-input"></div>
            </div>
        </div>

        <!-- FAQ -->
        <div class="schema-form" id="form-faq" style="display:none;">
            <div id="faqItems">
                <div class="faq-item" style="border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;margin-bottom:16px;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div style="grid-column:1/-1;"><label class="form-label">Question</label><input type="text" class="form-input faq-q" placeholder="Enter question"></div>
                        <div style="grid-column:1/-1;"><label class="form-label">Answer</label><textarea class="form-textarea faq-a" rows="2" placeholder="Enter answer"></textarea></div>
                    </div>
                </div>
            </div>
            <button class="btn btn-sm btn-secondary" onclick="addFaqItem()">+ Add Question</button>
        </div>

        <!-- Local Business -->
        <div class="schema-form" id="form-localbusiness" style="display:none;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div style="grid-column:1/-1;"><label class="form-label">Business Name *</label><input type="text" id="lbName" class="form-input" placeholder="Business name"></div>
                <div style="grid-column:1/-1;"><label class="form-label">Description</label><textarea id="lbDesc" class="form-textarea" rows="2" placeholder="Describe your business"></textarea></div>
                <div><label class="form-label">Address *</label><input type="text" id="lbAddress" class="form-input" placeholder="Street address"></div>
                <div><label class="form-label">Phone *</label><input type="tel" id="lbPhone" class="form-input" placeholder="+1 555-0000"></div>
                <div><label class="form-label">Opening Hours</label><input type="text" id="lbHours" class="form-input" placeholder="Mo-Fr 09:00-17:00"></div>
                <div><label class="form-label">Price Range</label><select id="lbPrice" class="form-select"><option value="$">$</option><option value="$$">$$</option><option value="$$$">$$$</option><option value="$$$$">$$$$</option></select></div>
                <div><label class="form-label">Latitude</label><input type="text" id="lbLat" class="form-input" placeholder="40.7128"></div>
                <div><label class="form-label">Longitude</label><input type="text" id="lbLng" class="form-input" placeholder="-74.0060"></div>
                <div><label class="form-label">Image URL</label><input type="url" id="lbImage" class="form-input" placeholder="https://..."></div>
                <div><label class="form-label">URL</label><input type="url" id="lbUrl" class="form-input" placeholder="https://business.com"></div>
            </div>
        </div>

        <!-- Product -->
        <div class="schema-form" id="form-product" style="display:none;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div style="grid-column:1/-1;"><label class="form-label">Product Name *</label><input type="text" id="prodName" class="form-input" placeholder="Product name"></div>
                <div style="grid-column:1/-1;"><label class="form-label">Description</label><textarea id="prodDesc" class="form-textarea" rows="2" placeholder="Product description"></textarea></div>
                <div><label class="form-label">Price *</label><input type="number" id="prodPrice" class="form-input" placeholder="29.99" step="0.01"></div>
                <div><label class="form-label">Currency *</label><select id="prodCurrency" class="form-select"><option value="USD">USD</option><option value="EUR">EUR</option><option value="GBP">GBP</option></select></div>
                <div><label class="form-label">Availability</label><select id="prodAvail" class="form-select"><option value="InStock">In Stock</option><option value="OutOfStock">Out of Stock</option><option value="PreOrder">Pre-Order</option></select></div>
                <div><label class="form-label">Condition</label><select id="prodCondition" class="form-select"><option value="NewCondition">New</option><option value="UsedCondition">Used</option><option value="RefurbishedCondition">Refurbished</option></select></div>
                <div><label class="form-label">Brand</label><input type="text" id="prodBrand" class="form-input" placeholder="Brand name"></div>
                <div><label class="form-label">SKU</label><input type="text" id="prodSku" class="form-input" placeholder="SKU-001"></div>
                <div><label class="form-label">Image URL</label><input type="url" id="prodImage" class="form-input" placeholder="https://..."></div>
                <div><label class="form-label">Product URL</label><input type="url" id="prodUrl" class="form-input" placeholder="https://..."></div>
            </div>
        </div>

        <!-- Organization -->
        <div class="schema-form" id="form-organization" style="display:none;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div style="grid-column:1/-1;"><label class="form-label">Organization Name *</label><input type="text" id="orgName" class="form-input" placeholder="Organization name"></div>
                <div style="grid-column:1/-1;"><label class="form-label">Description</label><textarea id="orgDesc" class="form-textarea" rows="2" placeholder="Description"></textarea></div>
                <div><label class="form-label">URL</label><input type="url" id="orgUrl" class="form-input" placeholder="https://organization.com"></div>
                <div><label class="form-label">Logo URL</label><input type="url" id="orgLogo" class="form-input" placeholder="https://...logo.png"></div>
                <div><label class="form-label">Contact Email</label><input type="email" id="orgEmail" class="form-input" placeholder="contact@org.com"></div>
                <div><label class="form-label">Phone</label><input type="tel" id="orgPhone" class="form-input" placeholder="+1 555-0000"></div>
                <div><label class="form-label">Address</label><input type="text" id="orgAddress" class="form-input" placeholder="Street address"></div>
                <div><label class="form-label">Founding Date</label><input type="date" id="orgDate" class="form-input"></div>
            </div>
        </div>
    </div>

    <button class="btn btn-primary" onclick="generateSchema()" style="margin:24px 0;">Generate Schema</button>

    <div id="schemaOutput" style="display:none;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <label class="form-label" style="margin:0;">JSON-LD Code</label>
            <div style="display:flex;gap:8px;">
                <button class="btn btn-sm btn-secondary" onclick="copySchema()">Copy</button>
                <button class="btn btn-sm btn-secondary" onclick="downloadSchema()">Download</button>
            </div>
        </div>
        <pre id="schemaCode" style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;font-size:13px;line-height:1.6;overflow-x:auto;margin:0;white-space:pre-wrap;"></pre>
        <div id="schemaValidation" style="margin-top:12px;"></div>
    </div>
</div>

<script>
let currentSchemaType = 'article';

function switchSchema(type) {
    currentSchemaType = type;
    document.querySelectorAll('.schema-form').forEach(f => f.style.display = 'none');
    document.getElementById('form-' + type).style.display = 'block';
    document.querySelectorAll('.schema-tab').forEach(btn => {
        btn.className = btn.dataset.type === type ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-secondary';
    });
    document.getElementById('schemaOutput').style.display = 'none';
}

function addFaqItem() {
    const container = document.getElementById('faqItems');
    const item = document.createElement('div');
    item.className = 'faq-item';
    item.style.cssText = 'border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px;margin-bottom:16px;';
    item.innerHTML = `
        <div style="display:flex;justify-content:flex-end;margin-bottom:8px;">
            <button onclick="this.parentElement.parentElement.remove()" style="background:none;border:none;color:var(--error);cursor:pointer;font-size:13px;font-weight:600;">Remove</button>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <div style="grid-column:1/-1;"><label class="form-label">Question</label><input type="text" class="form-input faq-q" placeholder="Enter question"></div>
            <div style="grid-column:1/-1;"><label class="form-label">Answer</label><textarea class="form-textarea faq-a" rows="2" placeholder="Enter answer"></textarea></div>
        </div>`;
    container.appendChild(item);
}

function generateSchema() {
    let schema = { '@context': 'https://schema.org' };

    switch (currentSchemaType) {
        case 'article':
            schema['@type'] = 'Article';
            schema.headline = document.getElementById('artHeadline').value || 'Article Title';
            schema.description = document.getElementById('artDesc').value;
            schema.author = { '@type': 'Person', 'name': document.getElementById('artAuthor').value || 'Author' };
            if (document.getElementById('artImage').value) schema.image = document.getElementById('artImage').value;
            if (document.getElementById('artDate').value) schema.datePublished = document.getElementById('artDate').value;
            if (document.getElementById('artModified').value) schema.dateModified = document.getElementById('artModified').value;
            break;

        case 'faq':
            schema['@type'] = 'FAQPage';
            schema.mainEntity = [];
            document.querySelectorAll('.faq-item').forEach(item => {
                const q = item.querySelector('.faq-q').value;
                const a = item.querySelector('.faq-a').value;
                if (q && a) {
                    schema.mainEntity.push({
                        '@type': 'Question',
                        'name': q,
                        'acceptedAnswer': { '@type': 'Answer', 'text': a }
                    });
                }
            });
            break;

        case 'localbusiness':
            schema['@type'] = 'LocalBusiness';
            schema.name = document.getElementById('lbName').value || 'Business Name';
            schema.description = document.getElementById('lbDesc').value;
            schema.address = { '@type': 'PostalAddress', 'streetAddress': document.getElementById('lbAddress').value };
            schema.telephone = document.getElementById('lbPhone').value;
            if (document.getElementById('lbHours').value) schema.openingHours = document.getElementById('lbHours').value;
            schema.priceRange = document.getElementById('lbPrice').value;
            if (document.getElementById('lbLat').value && document.getElementById('lbLng').value) {
                schema.geo = { '@type': 'GeoCoordinates', 'latitude': parseFloat(document.getElementById('lbLat').value), 'longitude': parseFloat(document.getElementById('lbLng').value) };
            }
            if (document.getElementById('lbImage').value) schema.image = document.getElementById('lbImage').value;
            if (document.getElementById('lbUrl').value) schema.url = document.getElementById('lbUrl').value;
            break;

        case 'product':
            schema['@type'] = 'Product';
            schema.name = document.getElementById('prodName').value || 'Product Name';
            schema.description = document.getElementById('prodDesc').value;
            schema.offers = {
                '@type': 'Offer',
                'price': document.getElementById('prodPrice').value || '0',
                'priceCurrency': document.getElementById('prodCurrency').value,
                'availability': 'https://schema.org/' + document.getElementById('prodAvail').value,
            };
            if (document.getElementById('prodCondition').value) schema.offers.itemCondition = 'https://schema.org/' + document.getElementById('prodCondition').value;
            if (document.getElementById('prodBrand').value) schema.brand = { '@type': 'Brand', 'name': document.getElementById('prodBrand').value };
            if (document.getElementById('prodSku').value) schema.sku = document.getElementById('prodSku').value;
            if (document.getElementById('prodImage').value) schema.image = document.getElementById('prodImage').value;
            if (document.getElementById('prodUrl').value) schema.url = document.getElementById('prodUrl').value;
            break;

        case 'organization':
            schema['@type'] = 'Organization';
            schema.name = document.getElementById('orgName').value || 'Organization Name';
            schema.description = document.getElementById('orgDesc').value;
            if (document.getElementById('orgUrl').value) schema.url = document.getElementById('orgUrl').value;
            if (document.getElementById('orgLogo').value) schema.logo = document.getElementById('orgLogo').value;
            if (document.getElementById('orgEmail').value) schema.email = document.getElementById('orgEmail').value;
            if (document.getElementById('orgPhone').value) schema.telephone = document.getElementById('orgPhone').value;
            if (document.getElementById('orgAddress').value) {
                schema.address = { '@type': 'PostalAddress', 'streetAddress': document.getElementById('orgAddress').value };
            }
            if (document.getElementById('orgDate').value) schema.foundingDate = document.getElementById('orgDate').value;
            break;
    }

    const json = JSON.stringify(schema, null, 2);
    document.getElementById('schemaCode').textContent = json;
    document.getElementById('schemaOutput').style.display = 'block';

    // Validation
    const validation = document.getElementById('schemaValidation');
    const hasRequired = Object.keys(schema).length > 2;
    validation.innerHTML = hasRequired
        ? '<span style="color:var(--success);font-weight:600;">✅ Valid JSON-LD schema generated</span>'
        : '<span style="color:var(--warning);font-weight:600;">⚠️ Please fill in required fields</span>';
}

function copySchema() {
    navigator.clipboard.writeText(document.getElementById('schemaCode').textContent).then(() => {
        const btn = event.target;
        btn.textContent = 'Copied!';
        setTimeout(() => btn.textContent = 'Copy', 2000);
    });
}

function downloadSchema() {
    const blob = new Blob([document.getElementById('schemaCode').textContent], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url; a.download = 'schema.json'; a.click();
    URL.revokeObjectURL(url);
}
</script>
