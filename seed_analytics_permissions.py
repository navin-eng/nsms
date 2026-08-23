import sqlite3
import json
from datetime import datetime

# Connect to database
conn = sqlite3.connect('database/database.sqlite')
c = conn.cursor()

permissions = [
    ('view_kpi_dashboard', 'web', 'View KPI Dashboard'),
    ('view_academic_analytics', 'web', 'View Academic Analytics'),
    ('view_attendance_analytics', 'web', 'View Attendance Analytics'),
    ('view_financial_analytics', 'web', 'View Financial Analytics'),
]

now = datetime.now().strftime('%Y-%m-%d %H:%M:%S')

# Check existing permissions
c.execute("SELECT name FROM permissions")
existing = [row[0] for row in c.fetchall()]

# Get Super Admin role id
c.execute("SELECT id FROM roles WHERE name='Super Admin'")
role_row = c.fetchone()
if not role_row:
    print("Super Admin role not found!")
    exit(1)
role_id = role_row[0]

for name, guard, label in permissions:
    if name not in existing:
        c.execute("INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES (?, ?, ?, ?)", (name, guard, now, now))
        permission_id = c.lastrowid
        # Assign to role
        c.execute("INSERT INTO role_has_permissions (permission_id, role_id) VALUES (?, ?)", (permission_id, role_id))
        print(f"Added and assigned permission: {name}")
    else:
        print(f"Permission {name} already exists.")

conn.commit()
conn.close()
print("Done seeding analytics permissions.")
