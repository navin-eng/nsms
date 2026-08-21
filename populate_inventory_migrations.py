import re

def insert_migration(file_path, content):
    with open(file_path, "r") as f:
        data = f.read()
    
    # Find the up method body
    match = re.search(r'Schema::create\(.*?, function \(Blueprint \$table\) \{(.*?)\}\);', data, re.DOTALL)
    if match:
        original = match.group(1)
        new_content = "\n            $table->id();\n" + content + "\n            $table->timestamps();\n        "
        data = data.replace(original, new_content)
        
        with open(file_path, "w") as f:
            f.write(data)

# 1. Categories
insert_migration("database/migrations/2026_08_21_170434_create_inventory_categories_table.php", """
            $table->string('name');
            $table->string('type')->default('Consumable'); // Consumable, Fixed Asset
            $table->text('description')->nullable();
""")

# 2. Stores
insert_migration("database/migrations/2026_08_21_170438_create_inventory_stores_table.php", """
            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
""")

# 3. Suppliers
insert_migration("database/migrations/2026_08_21_170442_create_inventory_suppliers_table.php", """
            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
""")

# 4. Items
insert_migration("database/migrations/2026_08_21_170444_create_inventory_items_table.php", """
            $table->foreignId('inventory_category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('sku_code')->nullable();
            $table->string('unit')->default('pcs'); // pcs, kg, boxes, etc.
            $table->text('description')->nullable();
            $table->integer('current_stock')->default(0); // Cached total stock based on purchases/issues/maintenance
""")

# 5. Purchases
insert_migration("database/migrations/2026_08_21_170446_create_inventory_purchases_table.php", """
            $table->foreignId('inventory_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inventory_supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('inventory_store_id')->nullable()->constrained()->nullOnDelete();
            
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2)->default(0);
            
            $table->date('purchase_date');
            $table->string('invoice_number')->nullable();
            $table->string('attachment')->nullable();
            
            $table->text('note')->nullable();
""")

# 6. Issues
insert_migration("database/migrations/2026_08_21_170449_create_inventory_issues_table.php", """
            $table->foreignId('inventory_item_id')->constrained()->cascadeOnDelete();
            
            // Polymorphic for Staff vs Department
            $table->string('issue_to_type'); // App\Models\Staff or App\Models\Department
            $table->unsignedBigInteger('issue_to_id');
            
            $table->integer('quantity');
            $table->date('issue_date');
            $table->date('due_date')->nullable();
            $table->date('return_date')->nullable();
            
            $table->string('status')->default('Issued'); // Issued, Returned, Overdue
            $table->text('note')->nullable();
""")

# 7. Maintenances
insert_migration("database/migrations/2026_08_21_170451_create_inventory_maintenances_table.php", """
            $table->foreignId('inventory_item_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity');
            $table->string('type'); // Damaged, Lost, Maintenance
            $table->date('date');
            
            $table->text('description')->nullable();
            $table->decimal('cost', 10, 2)->default(0);
            $table->string('status')->default('Pending'); // Pending, Repaired, Discarded
""")

