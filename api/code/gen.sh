python orm/orm.py
#find . -name '*.php' -not -name '*Model.php' -not -name 'repos*php' -exec mv {} ../../ \; -print
mv *Controller.php controllers/
mv database.php data/
mv *Model.php models/
mv index.php ../
