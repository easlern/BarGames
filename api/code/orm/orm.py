from xml.dom import minidom
xmldoc = minidom.parse ('models.xml')
models = xmldoc.getElementsByTagName ('model')
relationships = xmldoc.getElementsByTagName ('relationship')



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
            propertyName = prop.getAttribute('name')
            outputFile.write ('\t\tpublic function set' + cap(propertyName) + '($value){\n')
            outputFile.write ('\t\t\t$this->' + propertyName + ' = $value;\n')
            outputFile.write ('\t\t}\n')
        outputFile.write ('\n\t\tpublic function toStdClass(){\n')
        outputFile.write ('\t\t\t$std = new stdClass();\n')
        for prop in properties:
            propName = prop.getAttribute('name')
            outputFile.write ('\t\t\t$std->' + propName + ' = $this->get' + cap(propName) + '();\n')
        outputFile.write ('\t\t\treturn $std;\n')
        outputFile.write ('\t\t}\n')
        outputFile.write ('\t\tpublic function toJson(){')
        outputFile.write ('\n\t\t\treturn json_encode ($this->toStdClass());')
        outputFile.write ('\n\t\t}')
        outputFile.write ('\n\t}\n?>\n')
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
    outputFile.write ('\t\tif ($requestURI [$i] === $scriptName [$i])\n')
    outputFile.write ('\t\t{\n')
    outputFile.write ('\t\t\tunset($requestURI [$i]);\n')
    outputFile.write ('\t\t}\n')
    outputFile.write ('\t}\n')
    outputFile.write ('\t$route = $requestURI;\n')
    outputFile.write ('\tforeach ($route as $key=>$val){\n')
    outputFile.write ('\t\t$queryLoc = strpos ($val, "?");\n')
    outputFile.write ('\t\tif ($queryLoc !== FALSE) $route[$key] = substr ($val, 0, $queryLoc);\n')
    outputFile.write ('\t}\n')
    outputFile.write ('\n\t$command = SanitizeStringArray ($route);\n')
    outputFile.write ('\t$controllerName = array_values ($command);\n')
    outputFile.write ('\t$controllerName = strtolower ($controllerName [0]);\n')
    outputFile.write ('\t$args = array_filter (array_slice ($command, 1));\n')
    outputFile.write ('\t$controllerInstance = NULL;\n')
    outputFile.write ('\n\tswitch ($controllerName){\n')
    for model in models:
        modelName = model.getAttribute ('name')
        outputFile.write ('\t\tcase "' + modelName.lower() + '":\n')
        outputFile.write ('\t\t\t$controllerInstance = new ' + cap (modelName) + 'Controller();\n')
        outputFile.write ('\t\t\tbreak;\n')
    outputFile.write ('\t}\n')
    outputFile.write ('\n\tif ($controllerInstance !== NULL){\n')
    outputFile.write ('\t\tswitch ($_SERVER["REQUEST_METHOD"]){\n')
    outputFile.write ('\t\t\tcase "GET":\n')
    outputFile.write ('\t\t\t\tif (count ($args) == 0) $controllerInstance->getAll();\n')
    outputFile.write ('\t\t\t\telse $controllerInstance->get($args);\n')
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
    outputFile.write ('\t\theader("HTTP/1.1 404 Not Found");\n')
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

        # GET one
        outputFile.write ('\t\tpublic function get ($args){\n')
        outputFile.write ('\t\t\tif (count ($args) < 1){\n')
        outputFile.write ('\t\t\t\theader ("HTTP/1.1 400 Bad Request");\n')
        outputFile.write ('\t\t\t\t$errorObject = new ApiErrorResponse ("Missing required parameters.");\n')
        outputFile.write ('\t\t\t\tprint (json_encode ($errorObject));\n')
        outputFile.write ('\t\t\t\texit();\n')
        outputFile.write ('\t\t\t}\n')
        outputFile.write ('\n\t\t\t$repo = Repositories::get' + cap(modelName) + 'Repository();\n')
        outputFile.write ('\t\t\tif (IsAuthorized()){\n')
        outputFile.write ('\t\t\t\t$' + modelName + ' = $repo->getById ($args[0]);\n')
        outputFile.write ('\t\t\t\tif ($' + modelName + ' != NULL){\n')
        outputFile.write ('\t\t\t\t\theader ("HTTP/1.1 200 OK");\n')
        outputFile.write ('\t\t\t\t\tprint ($' + modelName + '->toJson());\n')
        outputFile.write ('\t\t\t\t}\n')
        outputFile.write ('\t\t\t\telse{\n')
        outputFile.write ('\t\t\t\t\theader ("HTTP/1.1 404 Not found");\n')
        outputFile.write ('\t\t\t\t}\n')
        outputFile.write ('\t\t\t}\n')
        outputFile.write ('\t\t\telse{\n')
        outputFile.write ('\t\t\t\theader ("HTTP/1.1 403 Forbidden");\n')
        outputFile.write ('\t\t\t\t$errorObject = new ApiErrorResponse("Not authenticated.");\n')
        outputFile.write ('\t\t\t\tprint (json_encode($errorObject));\n')
        outputFile.write ('\t\t\t}\n')
        outputFile.write ('\t\t}\n')
        # GET all
        outputFile.write ('\n\t\tpublic function getAll(){')
        outputFile.write ('\n\t\t\t$repo = Repositories::get' + cap(modelName) + 'Repository();\n')
        outputFile.write ('\t\t\tif (IsAuthorized()){\n')
        outputFile.write ('\t\t\t\t$' + modelName + ' = $repo->getAll();\n')
        outputFile.write ('\t\t\t\tif (count ($' + modelName + ') > 0){\n')
        outputFile.write ('\t\t\t\t\theader ("HTTP/1.1 200 OK");\n')
        outputFile.write ('\t\t\t\t\t$models = array();\n')
        outputFile.write ('\t\t\t\t\tforeach ($' + modelName + ' as &$model){\n')
        outputFile.write ('\t\t\t\t\t\tarray_push ($models, $model->toStdClass());\n')
        outputFile.write ('\t\t\t\t\t}\n')
        outputFile.write ('\t\t\t\t\tprint json_encode ($models);\n')
        outputFile.write ('\t\t\t\t}\n')
        outputFile.write ('\t\t\t\telse{\n')
        outputFile.write ('\t\t\t\t\theader ("HTTP/1.1 404 Not found");\n')
        outputFile.write ('\t\t\t\t}\n')
        outputFile.write ('\t\t\t}\n')
        outputFile.write ('\t\t\telse{\n')
        outputFile.write ('\t\t\t\theader ("HTTP/1.1 403 Forbidden");\n')
        outputFile.write ('\t\t\t\t$errorObject = new ApiErrorResponse("Not authenticated.");\n')
        outputFile.write ('\t\t\t\tprint (json_encode($errorObject));\n')
        outputFile.write ('\t\t\t}')
        outputFile.write ('\n\t\t}\n')

        # CREATE
        outputFile.write ('\n\t\tpublic function create ($args){\n')
        outputFile.write ('\t\t\tLogInfo ("Creating ' + modelName + ' with args: " . print_r ($args, true));\n')
        outputFile.write ('\t\t\t$argNamesSatisfied = TRUE;\n');
        outputFile.write ('\t\t\t$requiredArgs = array();\n');
        for prop in properties:
            if (prop.getAttribute ('required').lower() == 'always'):
                outputFile.write ('\t\t\tarray_push ($requiredArgs, "' + prop.getAttribute ('name') + '");\n');
        outputFile.write ('\t\t\tforeach ($requiredArgs as $requiredArg){\n');
        outputFile.write ('\t\t\t\tif (!in_array ($requiredArg, array_keys ($args))){\n');
        outputFile.write ('\t\t\t\t\t$argNamesSatisfied = FALSE;\n');
        outputFile.write ('\t\t\t\t}\n');
        outputFile.write ('\t\t\t}\n');
        outputFile.write ('\t\t\tif (!$argNamesSatisfied){\n')
        outputFile.write ('\t\t\t\theader ("HTTP/1.1 400 Bad Request");\n')
        outputFile.write ('\t\t\t\t$errorObject = new ApiErrorResponse ("Missing required parameters.");\n')
        outputFile.write ('\t\t\t\tprint (json_encode ($errorObject));\n')
        outputFile.write ('\t\t\t\texit();\n')
        outputFile.write ('\t\t\t}\n')

        outputFile.write ('\t\t\tif (IsAdminAuthorized() && IsCsrfGood()){\n')
        outputFile.write ('\t\t\t\t$repo = Repositories::get' + cap(modelName) + 'Repository();\n')
        for prop in properties:
            if (prop.getAttribute ('type') != 'primary key'):
                outputFile.write ('\t\t\t\t$' + prop.getAttribute ('name') + ' = ')
                if ('integer array' in prop.getAttribute ('data')):
                    outputFile.write ('array();\n')
                    outputFile.write ('\t\t\t\tif (in_array ("' + prop.getAttribute ('name') + '", array_keys ($args))){\n')
                    outputFile.write ('\t\t\t\t\t$decodedArray = json_decode ($args ["' + prop.getAttribute ('name') + '"], TRUE, 1);\n')
                    outputFile.write ('\t\t\t\t\tforeach ($decodedArray as $key => $value){\n')
                    outputFile.write ('\t\t\t\t\t\tarray_push ($decodedArray, $value);\n')
                    outputFile.write ('\t\t\t\t\t}\n')
                    outputFile.write ('\t\t\t\t}\n')
                else:
                    dbType = propDataToDbType (prop.getAttribute ('data').lower())
                    default = 0
                    if (dbType == 'varchar'):
                        default = '""'
                    outputFile.write ('in_array ("' + prop.getAttribute ('name') + '", array_keys ($args)) ? $args["' + prop.getAttribute ('name') + '"] : ' + str (default) + ';\n')
        outputFile.write ('\t\t\t\t$model = new ' + cap(modelName) + '(-1')
        count = 0
        for prop in properties:
            if (prop.getAttribute ('type') != 'primary key'):
                outputFile.write (', $' + prop.getAttribute ('name'))
                count += 1
        outputFile.write (');\n')
        outputFile.write ('\t\t\t\tif ($repo->create($model)){\n')
        outputFile.write ('\t\t\t\t\theader ("HTTP/1.1 303 See Other");\n')
        outputFile.write ('\t\t\t\t\theader ("Location: /api/' + modelName + '/" . $model->getId());\n')
        outputFile.write ('\t\t\t\t}\n')
        outputFile.write ('\t\t\t\telse{\n')
        outputFile.write ('\t\t\t\t\theader ("HTTP/1.1 500 Internal Server Error");\n')
        outputFile.write ('\t\t\t\t}\n')
        outputFile.write ('\t\t\t}\n')
        outputFile.write ('\t\t\telse{\n')
        outputFile.write ('\t\t\t\theader ("HTTP/1.1 403 Forbidden");\n')
        outputFile.write ('\t\t\t\t$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");\n')
        outputFile.write ('\t\t\t\tprint (json_encode($errorObject));\n')
        outputFile.write ('\t\t\t}\n')
        outputFile.write ('\t\t}\n')

        # UPDATE
        outputFile.write ('\n\t\tpublic function update ($args){\n')
        outputFile.write ('\t\t\tLogInfo ("Updating ' + modelName + ' with args: " . print_r ($args, true));\n')
        outputFile.write ('\t\t\t$repo = Repositories::get' + cap(modelName) + 'Repository();\n')
        outputFile.write ('\t\t\t$existing = $repo->getById ($args[0]);\n')
        outputFile.write ('\t\t\tif ($existing == NULL){\n')
        outputFile.write ('\t\t\t\theader ("HTTP/1.1 404 Not Found");\n')
        outputFile.write ('\t\t\t\t$errorObject = new ApiErrorResponse ("Missing required parameters.");\n')
        outputFile.write ('\t\t\t\tprint (json_encode ($errorObject));\n')
        outputFile.write ('\t\t\t\texit();\n')
        outputFile.write ('\t\t\t}\n')
        
        outputFile.write ('\t\t\tif (IsAdminAuthorized() && IsCsrfGood()){\n')
        outputFile.write ('\t\t\t\tforeach ($args as $key => $value){\n')
        for prop in properties:
            if (prop.getAttribute ('type') == 'primary key' or 'array' in prop.getAttribute ('data')):
                continue
            propName = prop.getAttribute ('name')
            outputFile.write ('\t\t\t\t\tif ($key === "' + propName + '") $existing->set' + cap (propName) + ' ($value);\n')
        outputFile.write ('\t\t\t\t}\n')
        outputFile.write ('\t\t\t\tif ($repo->update ($existing)){\n')
        outputFile.write ('\t\t\t\t\theader ("HTTP/1.1 200 OK");\n')
        outputFile.write ('\t\t\t\t}\n')
        outputFile.write ('\t\t\t\telse{\n')
        outputFile.write ('\t\t\t\t\theader ("HTTP/1.1 500 Internal Server Error");\n')
        outputFile.write ('\t\t\t\t}\n')
        outputFile.write ('\t\t\t}\n')
        outputFile.write ('\t\t\telse{\n')
        outputFile.write ('\t\t\t\theader ("HTTP/1.1 403 Forbidden");\n')
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
        outputFile.write ('\t\t\tLogInfo ("Deleting ' + modelName + ' with args: " . print_r ($args, true));\n')
        outputFile.write ('\t\t\tif (count ($args) < ' + str (primaryPropCount) + '){\n')
        outputFile.write ('\t\t\t\theader ("HTTP/1.1 400 Bad Request");\n')
        outputFile.write ('\t\t\t\t$errorObject = new ApiErrorResponse ("Missing required parameters.");\n')
        outputFile.write ('\t\t\t\tprint (json_encode ($errorObject));\n')
        outputFile.write ('\t\t\t\texit();\n')
        outputFile.write ('\t\t\t}\n')
        
        outputFile.write ('\t\t\tif (IsAdminAuthorized() && IsCsrfGood()){\n')
        outputFile.write ('\t\t\t\tLogInfo ("Delete is authorized.");\n')
        outputFile.write ('\t\t\t\t$repo = Repositories::get' + cap(modelName) + 'Repository();\n')
        outputFile.write ('\t\t\t\t$repo->delete ($args[0]);\n')
        outputFile.write ('\t\t\t\theader ("HTTP/1.1 204 No Content");\n')
        outputFile.write ('\t\t\t}\n')
        outputFile.write ('\t\t\telse{\n')
        outputFile.write ('\t\t\t\tLogInfo ("Delete is not authorized.");\n')
        outputFile.write ('\t\t\t\theader ("HTTP/1.1 403 Forbidden");\n')
        outputFile.write ('\t\t\t\t$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");\n')
        outputFile.write ('\t\t\t\tprint (json_encode($errorObject));\n')
        outputFile.write ('\t\t\t}\n')
        
        outputFile.write ('\t\t}\n')

        outputFile.write ('\t}\n')
        outputFile.write ('\n?>\n')
    outputFile.close()

def makeRepositories():
    outputFile = open ('repositories.php', 'w')
    outputFile.write ('<?php\n')
    outputFile.write ('\n\trequire_once ("startup.php");')
    outputFile.write ('\n\trequire_once ("initializeDb.php");\n\n')
    for model in models:
        modelName = model.getAttribute ('name')
        properties = model.getElementsByTagName ('property')
        outputFile.write ('\n\tclass MySql' + cap(modelName) + 'Repository{\n')
        outputFile.write ('\t\tpublic function create ($model){')
        outputFile.write ('\n\t\t\tLogInfo ("Creating ' + modelName + '");\n')
        outputFile.write ('\n\t\t\t$conn = connectAsWebUser();')
        outputFile.write ('\n\t\t\tif (!$conn) return NULL;')
        outputFile.write ('\n\t\t\t$statement = $conn->prepare ("insert into ' + modelName + ' (')
        first = True
        nonPrimaryPropCount = 0
        for prop in properties:
            if (prop.getAttribute ('type') != 'primary key' and 'array' not in prop.getAttribute ('data').lower()):
                if (not first):
                    outputFile.write (', ')
                first = False
                nonPrimaryPropCount += 1
                outputFile.write (prop.getAttribute ('name'))
        outputFile.write (') values (')
        for count in range (nonPrimaryPropCount):
            if (count != 0):
                outputFile.write (', ')
            outputFile.write ('?')
        outputFile.write (')");')
        outputFile.write ('\n\t\t\t$statement->bind_param ("')
        for prop in properties:
            if ('array' not in prop.getAttribute ('data').lower() and 'primary key' != prop.getAttribute ('type')):
                dbType = propDataToDbType (prop.getAttribute ('data').lower())
                if (dbType == 'varchar'):
                    outputFile.write ('s')
                elif (dbType == 'int'):
                    outputFile.write ('i')
                elif (dbType == 'float'):
                    outputFile.write ('d')
                elif (dbType == 'decimal'):
                    outputFile.write ('d')
        outputFile.write ('", ')
        first = True
        for prop in properties:
            if ('array' not in prop.getAttribute ('data').lower() and 'primary key' != prop.getAttribute ('type')):
                if (not first):
                    outputFile.write (', ')
                first = False
                outputFile.write ('$model->get' + cap (prop.getAttribute ('name')) + '()')
        outputFile.write (');')
        outputFile.write ('\n\t\t\t$result = $statement->execute();')
        outputFile.write ('\n\t\t\tif (!$result){')
        outputFile.write ('\n\t\t\t\tLogInfo ("SQL error: " . $statement->error);')
        outputFile.write ('\n\t\t\t\treturn FALSE;')
        outputFile.write ('\n\t\t\t}')
        outputFile.write ('\n\t\t\t$model->setId ($conn->insert_id);')
        outputFile.write ('\n\t\t\treturn TRUE;')
        outputFile.write ('\n\t\t}')
        outputFile.write ('\n')
        outputFile.write ('\t\tpublic function getById ($id){')
        outputFile.write ('\n\t\t\t$conn = connectAsWebUser();')
        outputFile.write ('\n\t\t\tif (!$conn) return NULL;')
        outputFile.write ('\n\t\t\t$result = $conn->prepare ("select id')
        for prop in properties:
            if (prop.getAttribute ('type').lower() != 'primary key' and 'array' not in prop.getAttribute ('data').lower()):
                outputFile.write (', ' + prop.getAttribute ('name'))
        outputFile.write (' from ' + modelName + ' where id = ?");')
        outputFile.write ('\n\t\t\t$result->bind_param ("i", $id);')
        outputFile.write ('\n\t\t\t$result->execute();\n')
        for prop in properties:
            if ('array' not in prop.getAttribute ('data').lower()):
                outputFile.write ('\n\t\t\t$' + prop.getAttribute ('name') + ' = ')
                if (propDataToDbType (prop.getAttribute ('data').lower()) == 'varchar'):
                    outputFile.write ('"";')
                else:
                    outputFile.write ('0;')
        outputFile.write ('\n\t\t\t$result->bind_result (');
        first = True
        for prop in properties:
            if ('array' not in prop.getAttribute ('data').lower()):
                if (not first):
                    outputFile.write (', ')
                first = False
                outputFile.write ('$' + prop.getAttribute ('name'));
        outputFile.write (');')
        outputFile.write ('\n\t\t\tif ($result->fetch()){')
        outputFile.write ('\n\t\t\t\treturn new ' + cap(modelName) + ' (')
        first = True
        for prop in properties:
            if (not first):
                outputFile.write (', ')
            first = False
            if ('array' in prop.getAttribute ('data').lower()):
                outputFile.write ('array()')
            else:
                outputFile.write ('$' + prop.getAttribute ('name'))
        outputFile.write (');')
        outputFile.write ('\n\t\t\t}')
        outputFile.write ('\n\t\t\treturn NULL;')
        outputFile.write ('\n\t\t}\n')

        # DELETE
        outputFile.write ('\n\t\tpublic function delete ($id){')
        outputFile.write ('\n\t\t\tLogInfo ("Deleting in repo ' + modelName + ' with id $id.");')
        outputFile.write ('\n\t\t\t$conn = connectAsWebUser();')
        outputFile.write ('\n\t\t\tif (!$conn) return NULL;')
        outputFile.write ('\n\t\t\t$statement = $conn->prepare ("delete from ' + modelName + ' where id = ?");')
        outputFile.write ('\n\t\t\t$statement->bind_param ("i", $id);')
        outputFile.write ('\n\t\t\t$statement->execute();')
        outputFile.write ('\n\t\t}')

        # UPDATE
        outputFile.write ('\n\t\tpublic function update ($model){')
        outputFile.write ('\n\t\t\t$conn = connectAsWebUser();')
        outputFile.write ('\n\t\t\tif (!$conn) return FALSE;')
        outputFile.write ('\n\t\t\t$statement = $conn->prepare ("update ' + modelName + ' set ')
        first = True
        for prop in properties:
            if (prop.getAttribute ('type') != 'primary key' and 'array' not in prop.getAttribute ('data').lower()):
                if (not first):
                    outputFile.write (', ')
                first = False
                name = prop.getAttribute ('name')
                outputFile.write (name + ' = ?')
        outputFile.write (' where id = ?");')
        outputFile.write ('\n\t\t\t$statement->bind_param ("')
        for prop in properties:
            if ('array' not in prop.getAttribute ('data').lower() and 'primary key' != prop.getAttribute ('type')):
                dbType = propDataToDbType (prop.getAttribute ('data').lower())
                if (dbType == 'varchar'):
                    outputFile.write ('s')
                elif (dbType == 'int'):
                    outputFile.write ('i')
                elif (dbType == 'float'):
                    outputFile.write ('d')
                elif (dbType == 'decimal'):
                    outputFile.write ('d')
        outputFile.write ('i", ')
        first = True
        for prop in properties:
            if ('array' not in prop.getAttribute ('data').lower() and 'primary key' != prop.getAttribute ('type')):
                if (not first):
                    outputFile.write (', ')
                first = False
                outputFile.write ('$model->get' + cap (prop.getAttribute ('name')) + '()')
        outputFile.write (', $model->getId());')
        outputFile.write ('\n\t\t\t$result = $statement->execute();')
        outputFile.write ('\n\t\t\tif (!$result){')
        outputFile.write ('\n\t\t\t\tLogInfo ("SQL error: " . $statement->error);')
        outputFile.write ('\n\t\t\t\treturn FALSE;')
        outputFile.write ('\n\t\t\t}')
        outputFile.write ('\n\t\t\treturn TRUE;')
        outputFile.write ('\n\t\t}\n\n')

        outputFile.write ('\t\tpublic function getAll(){')
        outputFile.write ('\n\t\t\t$results = array();')
        outputFile.write ('\n\t\t\t$conn = connectAsWebUser();')
        outputFile.write ('\n\t\t\tif (!$conn) return $results;')
        outputFile.write ('\n\t\t\t$statement = $conn->prepare ("select id')
        for prop in properties:
            if (prop.getAttribute ('type').lower() != 'primary key' and 'array' not in prop.getAttribute ('data').lower()):
                outputFile.write (', ' + prop.getAttribute ('name'))
        outputFile.write (' from ' + modelName + '");')
        outputFile.write ('\n\t\t\t$statement->execute();\n')
        for prop in properties:
            if ('array' not in prop.getAttribute ('data').lower()):
                outputFile.write ('\n\t\t\t$' + prop.getAttribute ('name') + ' = ')
                if (propDataToDbType (prop.getAttribute ('data').lower()) == 'varchar'):
                    outputFile.write ('"";')
                else:
                    outputFile.write ('0;')
        outputFile.write ('\n\t\t\t$statement->bind_result (');
        first = True
        for prop in properties:
            if ('array' not in prop.getAttribute ('data').lower()):
                if (not first):
                    outputFile.write (', ')
                first = False
                outputFile.write ('$' + prop.getAttribute ('name'));
        outputFile.write (');')
        outputFile.write ('\n\t\t\t$statement->store_result();');
        outputFile.write ('\n\t\t\twhile ($statement->fetch()){')
        outputFile.write ('\n\t\t\t\t$model = new ' + cap(modelName) + ' (');
        first = True
        for prop in properties:
            if (not first):
                outputFile.write (', ')
            if ('array' in prop.getAttribute ('data')):
                outputFile.write ('array()')
            else:
                outputFile.write ('$' + prop.getAttribute ('name'));
            first = False
        outputFile.write (');')
        outputFile.write ('\n\t\t\t\tarray_push ($results, $model);')
        outputFile.write ('\n\t\t\t}')
        outputFile.write ('\n\t\t\treturn $results;')
        outputFile.write ('\n\t\t}\n')
        
        outputFile.write ('\n\t}\n')
        outputFile.write ('\n\tclass Test' + cap(modelName) + 'Repository{\n')
        outputFile.write ('\t\tpublic function getById ($id){\n')
        outputFile.write ('\t\t\treturn new ' + cap(modelName) + '($id')
        for prop in properties:
            if (prop.getAttribute ('type') == 'primary key'):
                continue
            outputFile.write (', ')
            data = prop.getAttribute ('data')
            if (data == 'string'):
                outputFile.write ('"test_' + prop.getAttribute ('name') + '"')
            if (data in ('integer', 'float', 'decimal')):
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
        outputFile.write ('\t\t\treturn new MySql' + cap(modelName) + 'Repository();\n')
        outputFile.write ('\t\t}\n')
    outputFile.write ('\t}\n')
    outputFile.write ('\n?>\n')
    outputFile.close()

def propDataToDbType (propData):
    dbMap = {'integer':'int', 'string':'varchar', 'integer array':'int', 'float':'float', 'decimal':'decimal'}
    if (not propData in dbMap):
        return ''
    return dbMap[propData]

def makeDatabase():
    outputFile = open ('database.php', 'w')
    outputFile.write ('<?php\n\n')
    outputFile.write ('\trequire_once ("initializeDb.php");\n\n')
    outputFile.write ('\tclass Database{\n')
    outputFile.write ('\t\tpublic function initialize(){\n')
    outputFile.write ('\t\t\t$con = connectAsAdmin();\n')
    for model in models:
        modelName = model.getAttribute ('name')
        properties = model.getElementsByTagName ('property')
        outputFile.write ('\t\t\t$con->query ("drop table if exists ' + modelName + '");\n')
        outputFile.write ('\t\t\t$con->query ("create table ' + modelName + ' (')
        firstProp = True
        for prop in properties:
            propName = prop.getAttribute ('name')
            propType = prop.getAttribute ('type')
            propData = prop.getAttribute ('data')
            propLength = prop.getAttribute ('length')
            if ('array' in propData):
                continue
            if (propLength == ''):
                propLength = '0'
            dbType = propDataToDbType (propData)
            if (dbType == 'varchar'):
                dbType = dbType + '(' + propLength + ')'
            if (not firstProp):
                outputFile.write (', ')
            outputFile.write (propName)
            outputFile.write (' ' + dbType)
            if (prop.getAttribute ('type') == 'primary key' or prop.getAttribute ('required') == 'always'):
                outputFile.write (' not null')
            if (prop.getAttribute ('type') == 'primary key' and propData == 'integer'):
                outputFile.write (' auto_increment')
            firstProp = False
        for prop in properties:
            propName = prop.getAttribute ('name')
            propType = prop.getAttribute ('type')
            if (propType == 'primary key'):
                outputFile.write (', primary key(' + propName + ')')
        outputFile.write (')");\n')
    for relationship in relationships:
        relType = relationship.getAttribute ('type')
        relName = relType
        if (relType == 'manyToOne'):
            relFromModel = relationship.getAttribute ('from')
            relToModel = relationship.getAttribute ('to')
            outputFile.write ('\t\t\t$con->query ("alter table ' + relFromModel + ' add constraint fk_' + relFromModel + '_' + relToModel + ' foreign key (' + relToModel + 'Id) references ' + relToModel + '(id)");\n')
        if (relType == 'manyToMany'):
            relName = 'mtm';
            relFromModel = relationship.getAttribute ('from')
            relToModel = relationship.getAttribute ('to')
            crossName = relName + "_" + relFromModel + "_" + relToModel
            outputFile.write ('\t\t\t$con->query ("drop table if exists ' + crossName + '");\n')
            outputFile.write ('\t\t\t$con->query ("create table ' + crossName + ' (')
            fromName = ''
            toName = ''
            for model in models:
                modelName = model.getAttribute ('name')
                for prop in model.getElementsByTagName ('property'):
                    propType = prop.getAttribute ('type')
                    propName = prop.getAttribute ('name')
                    propData = prop.getAttribute ('data')
                    propLength = prop.getAttribute ('length')
                    if (propLength == ''):
                        propLength = '0'
                    dbType = propDataToDbType (propData)
                    if (dbType == 'varchar'):
                        dbType = dbType + '(' + propLength + ')'
                    if (modelName == relFromModel and propType == 'primary key'):
                        fromName = modelName + cap (propName) + ' ' + propDataToDbType (propData) + ' not null'
                    if (modelName == relToModel and propType == 'primary key'):
                        toName = modelName + cap (propName) + ' ' + propDataToDbType (propData) + ' not null'
            outputFile.write (fromName + ', ' + toName)
            outputFile.write (')");\n')
    outputFile.write ('\t\t}\n')
    outputFile.write ('\t}\n')
    outputFile.write ('?>\n')
    outputFile.close()



makeObjects()
makeWebAccessors()
makeRepositories()
makeControllers()
makeDatabase()

       
    
