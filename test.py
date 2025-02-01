import pymysql

# Connect to the MySQL database using the 'mac' account
connection = pymysql.connect(
    host='localhost',        # Hostname
    user='mac',              # Username
    password='pass',         # Password
    database='Edupath_db' # Replace with your database name
)

try:
    with connection.cursor() as cursor:
        # Test the connection
        cursor.execute("SELECT DATABASE();")
        current_db = cursor.fetchone()
        print(f"Connected to database: {current_db}")
finally:
    connection.close()
