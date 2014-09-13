from xml.dom import minidom
xmldoc = minidom.parse('models.xml')
models = xmldoc.getElementsByTagName('model')

def cap (what):
    return what[0].capitalize() + what[1:]

def makeObjects():
    for model in models:
        modelName = model.getAttribute('name')
        properties = model.getElementsByTagName('property')
        outputFile = open (modelName + 'Model.php', 'w')
        outputFile.write ('<?php\n\tclass ' + cap(modelName) + '{\n')
        for prop in properties:
            propertyName = prop.getAttribute('name')
            outputFile.write ('\t\tprivate $' + propertyName + ';\n')
        outputFile.write ('\n')
        outputFile.write ('\t\tfunction __construct (')
        firstProp = True
        for prop in properties:
            if (not firstProp):
                outputFile.write (', ')
            outputFile.write ('$' + prop.getAttribute('name'))
            firstProp = False
        outputFile.write ('){\n')
        for prop in properties:
            propName = prop.getAttribute ('name')
            outputFile.write ('\t\t\t$this->' + propName + ' = $' + propName + ';\n')
        outputFile.write ('\t\t}\n\n')
        for prop in properties:
            propertyName = prop.getAttribute('name')
            outputFile.write ('\t\tpublic function get' + cap(propertyName) + '(){\n')
            outputFile.write ('\t\t\treturn $this->' + propertyName + ';\n')
            outputFile.write ('\t\t}\n')
        outputFile.write('\n')
        for prop in properties:
            if (prop.getAttribute ('type') == 'primary key'):
                continue
            propertyName = prop.getAttribute('name')
            outputFile.write ('\t\tpublic function set' + cap(propertyName) + '($value){\n')
            outputFile.write ('\t\t\t$this->' + propertyName + ' = $value;\n')
            outputFile.write ('\t\t}\n')
        outputFile.write ('\n\t\tpublic function toJson(){\n')
        outputFile.write ('\t\t\t$json = new stdClass();\n')
        for prop in properties:
            propName = prop.getAttribute('name')
            outputFile.write ('\t\t\t$json->' + propName + ' = $this->get' + cap(propName) + '();\n')
        outputFile.write ('\t\t\treturn json_encode ($json);\n')
        outputFile.write ('\t\t}\n')
        outputFile.write ('\t}\n?>\n')
        outputFile.close()

def makeWebAccessors():
    outputFile = open ('index.php', 'w')
    outputFile.write ('<?php\n')
    outputFile.write ('\trequire_once (\'code/startup.php\');\n')
    for model in models:
        modelName = model.getAttribute ('name')
        outputFile.write ('\trequire_once ("' + modelName + 'Controller.php");\n')
    outputFile.write ('\n\tif (strlen($_SERVER[\'REQUEST_URI\']) > 1024) exit();\n')
    outputFile.write ('\n\t$requestURI = explode(\'/\', $_SERVER[\'REQUEST_URI\']);\n')
    outputFile.write ('\t$scriptName = explode(\'/\',$_SERVER[\'SCRIPT_NAME\']);\n')
    outputFile.write ('\tfor($i = 0; $i < sizeof ($scriptName); $i++)\n')
    outputFile.write ('\t{\n')
    outputFile.write ('\t\tif ($requestURI [$i] == $scriptName [$i])\n')
    outputFile.write ('\t\t{\n')
    outputFile.write ('\t\t\tunset($requestURI [$i]);\n')
    outputFile.write ('\t\t}\n')
    outputFile.write ('\t}\n')
    outputFile.write ('\t$route = $requestURI;\n')
    outputFile.write ('\tforeach ($route as $key=>$val){\n')
    outputFile.write ('\t\t$queryLoc = strpos ($val, "?");\n')
    outputFile.write ('\t\tif ($queryLoc !== FALSE) $route[$key] = substr ($val, 0, $queryLoc);\n')
    outputFile.write ('\t}\n')
    outputFile.write ('\n\t$command = SanitizeStringArray (array_values ($route));\n')
    outputFile.write ('\t$controllerName = strtolower ($command [0]);\n')
    outputFile.write ('\t$args = array_slice ($command, 1);\n')
    outputFile.write ('\t$controllerInstance = NULL;\n')
    outputFile.write ('\n\tswitch ($controllerName){\n')
    for model in models:
        modelName = model.getAttribute ('name')
        outputFile.write ('\t\tcase "' + modelName + '":\n')
        outputFile.write ('\t\t\t$controllerInstance = new ' + cap (modelName) + 'Controller();\n')
        outputFile.write ('\t\t\tbreak;\n')
    outputFile.write ('\t}\n')
    outputFile.write ('\n\tif ($controllerInstance !== NULL){\n')
    outputFile.write ('\t\tswitch ($_SERVER["REQUEST_METHOD"]){\n')
    outputFile.write ('\t\t\tcase "GET":\n')
    outputFile.write ('\t\t\t\t$controllerInstance->get($args);\n')
    outputFile.write ('\t\t\t\tbreak;\n')
    outputFile.write ('\t\t\tcase "POST":\n')
    outputFile.write ('\t\t\t\t$args = GetSanitizedPostVars();\n')
    outputFile.write ('\t\t\t\t$controllerInstance->create($args);\n')
    outputFile.write ('\t\t\t\tbreak;\n')
    outputFile.write ('\t\t\tcase "PUT":\n')
    outputFile.write ('\t\t\t\t$id = $args[0]; // Pull the index to be updated.\n')
    outputFile.write ('\t\t\t\t$args = GetSanitizedPutVars();\n')
    outputFile.write ('\t\t\t\tif (is_numeric ($id)) array_unshift ($args, $id);\n')
    outputFile.write ('\t\t\t\t$controllerInstance->update($args);\n')
    outputFile.write ('\t\t\t\tbreak;\n')
    outputFile.write ('\t\t\tcase "DELETE":\n')
    outputFile.write ('\t\t\t\t$id = $args[0]; // Pull the index to be deleted.\n')
    outputFile.write ('\t\t\t\t$args = GetSanitizedDeleteVars();\n')
    outputFile.write ('\t\t\t\tif (is_numeric ($id)) array_unshift ($args, $id);\n')
    outputFile.write ('\t\t\t\t$controllerInstance->delete($args);\n')
    outputFile.write ('\t\t\t\tbreak;\n')
    outputFile.write ('\t\t\tdefault:\n')
    outputFile.write ('\t\t\t\tLogInfo ($_SERVER["REQUEST_METHOD"] . " method not allowed.");\n')
    outputFile.write ('\t\t\t\theader ("HTTP/1.1 405 Method Not Allowed");\n')
    outputFile.write ('\t\t}\n')
    outputFile.write ('\t}\n')
    outputFile.write ('\telse{\n')
    outputFile.write ('\t\theader("HTTP/1.0 404 Not Found");\n')
    outputFile.write ('\t}\n')
    outputFile.write ('\n?>\n')

def makeControllers():
    for model in models:
        modelName = model.getAttribute('name')
        properties = model.getElementsByTagName('property')
        outputFile = open (modelName + 'Controller.php', 'w')
        outputFile.write ('<?php\n\n')
        outputFile.write ('\trequire_once (\'code/startup.php\');\n')
        outputFile.write ('\trequire_once (\'' + modelName + 'Model.php\');\n')
        outputFile.write ('\trequire_once (\'restfulSetup.php\');\n')
        outputFile.write ('\trequire_once (\'repositories.php\');\n')
        outputFile.write ('\n\tclass ' + cap (modelName) + 'Controller{\n')

        # GET
        outputFile.write ('\t\tpublic function get ($args){\n')
        outputFile.write ('\t\t\tif (count ($args) < 1){\n')
        outputFile.write ('\t\t\t\theader ("HTTP/1.1 500 Internal Server Error");\n')
        outputFile.write ('\t\t\t\t$errorObject = new ApiErrorResponse ("Missing required parameters.");\n')
        outputFile.write ('\t\t\t\tprint (json_encode ($errorObject));\n')
        outputFile.write ('\t\t\t\texit();\n')
        outputFile.write ('\t\t\t}\n')
        outputFile.write ('\n\t\t\t$repo = Repositories::get' + cap(modelName) + 'Repository();\n')
        outputFile.write ('\t\t\tif (IsAuthorized()){\n')
        outputFile.write ('\t\t\t\theader ("HTTP/1.1 200 OK");\n')
        outputFile.write ('\t\t\t\t$' + modelName + ' = $repo->get' + cap(modelName) + 'ById($args[0]);\n')
        outputFile.write ('\t\t\t\tprint ($' + modelName + '->toJson());\n')
        outputFile.write ('\t\t\t}\n')
        outputFile.write ('\t\t\telse{\n')
        outputFile.write ('\t\t\t\theader ("HTTP/1.1 500 Internal Server Error");\n')
        outputFile.write ('\t\t\t\t$errorObject = new ApiErrorResponse("Not authenticated.");\n')
        outputFile.write ('\t\t\t\tprint (json_encode($errorObject));\n')
        outputFile.write ('\t\t\t}\n')
        outputFile.write ('\t\t}\n')

        # CREATE
        outputFile.write ('\n\t\tpublic function create ($args){\n')
        nonPrimaryPropCount = 0
        for prop in properties:
            #print (prop.getAttribute ('type'))
            if (prop.getAttribute ('type') != 'primary key'):
                nonPrimaryPropCount += 1
        outputFile.write ('\t\t\tif (count ($args) < ' + str (nonPrimaryPropCount) + '){\n')
        outputFile.write ('\t\t\t\theader ("HTTP/1.1 500 Internal Server Error");\n')
        outputFile.write ('\t\t\t\t$errorObject = new ApiErrorResponse ("Missing required parameters.");\n')
        outputFile.write ('\t\t\t\tprint (json_encode ($errorObject));\n')
        outputFile.write ('\t\t\t\texit();\n')
        outputFile.write ('\t\t\t}\n')

        outputFile.write ('\t\t\tif (IsAdminAuthorized() && IsCsrfGood()){\n')
        outputFile.write ('\t\t\t\theader ("HTTP/1.1 303 See Other");\n')
        outputFile.write ('\t\t\t\theader ("Location: /BarGames/api/' + modelName + '/1");\n')
        outputFile.write ('\t\t\t}\n')
        outputFile.write ('\t\t\telse{\n')
        outputFile.write ('\t\t\t\theader ("HTTP/1.1 500 Internal Server Error");\n')
        outputFile.write ('\t\t\t\t$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");\n')
        outputFile.write ('\t\t\t\tprint (json_encode($errorObject));\n')
        outputFile.write ('\t\t\t}\n')
        outputFile.write ('\t\t}\n')

        # UPDATE
        outputFile.write ('\n\t\tpublic function update ($args){\n')
        nonPrimaryPropCount = 0
        for prop in properties:
            #print (prop.getAttribute ('type'))
            if (prop.getAttribute ('type') != 'primary key'):
                nonPrimaryPropCount += 1
        outputFile.write ('\t\t\tif (count ($args) < ' + str (nonPrimaryPropCount) + '){\n')
        outputFile.write ('\t\t\t\theader ("HTTP/1.1 500 Internal Server Error");\n')
        outputFile.write ('\t\t\t\t$errorObject = new ApiErrorResponse ("Missing required parameters.");\n')
        outputFile.write ('\t\t\t\tprint (json_encode ($errorObject));\n')
        outputFile.write ('\t\t\t\texit();\n')
        outputFile.write ('\t\t\t}\n')
        
        outputFile.write ('\t\t\tif (IsAdminAuthorized() && IsCsrfGood()){\n')
        outputFile.write ('\t\t\t\theader ("HTTP/1.1 200 OK");\n')
        outputFile.write ('\t\t\t}\n')
        outputFile.write ('\t\t\telse{\n')
        outputFile.write ('\t\t\t\theader ("HTTP/1.1 500 Internal Server Error");\n')
        outputFile.write ('\t\t\t\t$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");\n')
        outputFile.write ('\t\t\t\tprint (json_encode($errorObject));\n')
        outputFile.write ('\t\t\t}\n')
        outputFile.write ('\t\t}\n')

        # DELETE
        outputFile.write ('\n\t\tpublic function delete ($args){\n')
        primaryPropCount = 0
        for prop in properties:
            #print (prop.getAttribute ('type'))
            if (prop.getAttribute ('type') == 'primary key'):
                primaryPropCount += 1
        outputFile.write ('\t\t\tLogInfo ("Deleting ' + modelName + ' with args " . print_r ($args, true));\n')
        outputFile.write ('\t\t\tif (count ($args) < ' + str (primaryPropCount) + '){\n')
        outputFile.write ('\t\t\t\theader ("HTTP/1.1 500 Internal Server Error");\n')
        outputFile.write ('\t\t\t\t$errorObject = new ApiErrorResponse ("Missing required parameters.");\n')
        outputFile.write ('\t\t\t\tprint (json_encode ($errorObject));\n')
        outputFile.write ('\t\t\t\texit();\n')
        outputFile.write ('\t\t\t}\n')
        
        outputFile.write ('\t\t\tif (IsAdminAuthorized() && IsCsrfGood()){\n')
        outputFile.write ('\t\t\t\theader ("HTTP/1.1 204 No Content");\n')
        outputFile.write ('\t\t\t}\n')
        outputFile.write ('\t\t\telse{\n')
        outputFile.write ('\t\t\t\theader ("HTTP/1.1 500 Internal Server Error");\n')
        outputFile.write ('\t\t\t\t$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");\n')
        outputFile.write ('\t\t\t\tprint (json_encode($errorObject));\n')
        outputFile.write ('\t\t\t}\n')
        
        outputFile.write ('\t\t}\n')

        outputFile.write ('\t}\n')
        outputFile.write ('\n?>\n')
    outputFile.close()

def makeRepositories():
    outputFile = open ('repositories.php', 'w')
    outputFile.write ('<?php\n\n')
    for model in models:
        modelName = model.getAttribute ('name')
        properties = model.getElementsByTagName ('property')
        outputFile.write ('\tclass Test' + cap(modelName) + 'Repository{\n')
        outputFile.write ('\t\tpublic function get' + cap(modelName) + 'ById ($id){\n')
        outputFile.write ('\t\t\treturn new ' + cap(modelName) + '($id')
        for prop in properties:
            if (prop.getAttribute ('type') == 'primary key'):
                continue
            outputFile.write (', ')
            data = prop.getAttribute ('data')
            if (data == 'string'):
                outputFile.write ('"test_' + prop.getAttribute ('name') + '"')
            if (data == 'integer'):
                outputFile.write ('0')
            if (data == 'integer array'):
                outputFile.write ('array(0,1,2)')
        outputFile.write (');\n')
        outputFile.write ('\t\t}\n')
        outputFile.write ('\t}\n')
    outputFile.write ('\n\tclass Repositories{\n')
    for model in models:
        modelName = model.getAttribute('name')
        properties = model.getElementsByTagName('property')
        outputFile.write ('\t\tpublic static function get' + cap(modelName) + 'Repository(){\n')
        outputFile.write ('\t\t\treturn new Test' + cap(modelName) + 'Repository();\n')
        outputFile.write ('\t\t}\n')
    outputFile.write ('\t}\n')
    outputFile.write ('\n?>\n')
    outputFile.close()


makeObjects()
makeWebAccessors()
makeRepositories()
makeControllers()
        
    
