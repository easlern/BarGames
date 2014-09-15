python orm.py
#find . -name '*.php' -not -name '*Model.php' -not -name 'repos*php' -exec mv {} ../../ \; -print
mv *Controller.php ../controllers/
mv index.php ../../
mv database.php ../data/
