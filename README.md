# SQLSprinkler

## A lightweight web page to control sprinkler systems on a raspberry pi.

### SETUP


    * On debian, run the following command (after updating):
        `# apt install apache2 php libapache2-mod-php php-mysql phpmyadmin`
    * Make sure that "AllowOverride" in `/etc/apache/apache2.conf` is sat to `All`
    * Run:
        * `# a2enmod php`
        * `# a2ensite phpmyadmin`
        * `# apachectl restart`
    * Once you verify that apache is running properly, navigate to your phpmyadmin page, and create a database called `SQLSprinkler`
        * (Recommended) You may wish to create a user that only has access to this database.  
        1) Create the following table (4 rows, non-null) named `Systems`:
            1)
                * Name: 'Name'
                * Type: 'text'
            2)
                * Name: 'GPIO'
                * Type: int
            3)
                * Name: 'Time'
                * Type: int
            4)
                * Name: 'Days'
                * Type: int
            5)
                * Name: 'id'
                * Type: int
                * Autoincrement
        2) Update the .env file to contain the username and password of the user you used to edit the database.
        3) Run `compose install` in the project root directory
        4) Navigate to `http://localhost/SQLSprinkler` to see if the page loads.
        5) Click on the bottom left icon, click on settings, then click on the bottom left icon again, then click the plus sign to start adding sprinkler systems.
