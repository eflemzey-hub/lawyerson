# Hosting Your Law Firm CMS on Local XAMPP

This guide will walk you through hosting your website on your own computer using XAMPP so you can test it exactly as it would run on a live server.

---

## Step 1: Install XAMPP

1. **Download:** Go to [apachefriends.org](https://www.apachefriends.org/) and download XAMPP for Windows.
2. **Install:** Run the installer. You can leave all the default settings checked (make sure Apache and MySQL are checked). 
3. **Finish:** Once installed, open the **XAMPP Control Panel**.

---

## Step 2: Start Your Local Servers

1. In the XAMPP Control Panel, you will see a list of modules.
2. Click the **Start** button next to **Apache**.
3. Click the **Start** button next to **MySQL**.
4. Both should turn green, indicating your local web server and database are running.

---

## Step 3: Move Your Files to XAMPP

XAMPP has a specific folder where it looks for website files, called `htdocs`.

1. Open your File Explorer and navigate to `C:\xampp\htdocs`.
2. Create a new folder inside `htdocs` and call it something simple, like `lawfirm`.
3. Copy **all** the files and folders from your current project folder (`C:\Users\EMMA PC\Documents\Law Firm Template\Law firm lawyerson`) and paste them inside `C:\xampp\htdocs\lawfirm`.

---

## Step 4: Set Up the Database in phpMyAdmin

Now we need to create the database that your website will connect to.

1. Open your web browser and type `http://localhost/phpmyadmin` in the address bar.
2. On the left sidebar, click **New** to create a new database.
3. Under "Database name", type `law_firm_db` (this must match exactly what is in your `config/database.php` file) and click **Create**.

---

## Step 5: Import the Tables and Data

Now we need to fill the empty database with your tables (like attorneys, messages, etc.).

1. Click on your newly created `law_firm_db` database on the left sidebar so it is selected.
2. Click the **Import** tab at the top of the screen.
3. Click **Choose File**.
4. Navigate to your `C:\xampp\htdocs\lawfirm` folder and select the `database.sql` file.
5. Scroll to the bottom of the page and click the **Import** (or **Go**) button.
6. You should see a green success message saying the tables were created.

---

## Step 6: Test the Website!

1. Open a new tab in your web browser.
2. Type `http://localhost/lawfirm` in the address bar and press Enter.

Your Law Firm website should now load perfectly!

---

### Accessing the Admin Dashboard locally:

*   **URL:** `http://localhost/lawfirm/index.php?page=admin_login`
*   **Email:** `admin@lawyerson.com`
*   **Password:** `password123`

*(Remember, whenever you want to test the site in the future, just open the XAMPP Control Panel and make sure Apache and MySQL are running!)*
