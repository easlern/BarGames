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
    outputFile.write ('\trequire_once(\'code/startup.php\');\n')
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
    outputFile.write ('\n\t$command = SanitizeStringArray (array_values ($requestURI));\n')
    outputFile.write ('\t$controller = strtolower ($command [0]);\n')
    outputFile.write ('\t$args = array_slice ($command, 1);\n')
    outputFile.write ('\n\tswitch ($controller){\n')
    for model in models:
        modelName = model.getAttribute ('name')
        outputFile.write ('\t\tcase "' + modelName + '":\n')
        outputFile.write ('\t\t\t$cont = new ' + modelName + '();\n')
        outputFile.write ('\t\t\t$cont->handle ($args);\n')
        outputFile.write ('\t\t\tbreak;\n')
    outputFile.write ('\t}\n')
    outputFile.write ('?>\n')
    for model in models:
        modelName = model.getAttribute('name')
        properties = model.getElementsByTagName('property')
        outputFile = open (modelName + 'Controller.php', 'w')
        outputFile.write ('<?php\n\n')
        outputFile.write ('\trequire_once (\'code/startup.php\');\n')
        outputFile.write ('\trequire_once (\'' + modelName + 'Model.php\');\n')
        outputFile.write ('\trequire_once (\'restfulSetup.php\');\n')
        outputFile.write ('\trequire_once (\'repositories.php\');\n')
        outputFile.write ('\n\t$getVars = GetSanitizedGetVars();\n')
        outputFile.write ('\tif (')
        firstProp = True
        for prop in properties:
            if (prop.getAttribute ('type') != 'primary key'):
                continue
            propName = prop.getAttribute ('name')
            if (not firstProp):
                outputFile.write (' || ')
            outputFile.write ('!isset ($getVars["' + propName + '"])')
            firstProp = False
        outputFile.write ('){\n')
        outputFile.write ('\t\t$errorObject = new ApiErrorResponse ("Missing required parameters.");\n')
        outputFile.write ('\t\tprint (json_encode ($errorObject));\n')
        outputFile.write ('\t\texit();\n')
        outputFile.write ('\t}\n')
        outputFile.write ('\n\t$repo = Repositories::get' + cap(modelName) + 'Repository();\n')
        outputFile.write ('\tif (IsAuthorized() && IsCsrfGood()){\n')
        outputFile.write ('\t\t$' + modelName + ' = $repo->get' + cap(modelName) + 'ById($postVars["id"]);\n')
        outputFile.write ('\t\tprint ($' + modelName + '->toJson());\n')
        outputFile.write ('\t}\n')
        outputFile.write ('\telse{\n')
        outputFile.write ('\t\t$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");\n')
        outputFile.write ('\t\tprint (json_encode($errorObject));\n')
        outputFile.write ('\t}\n')
        outputFile.write ('\n\t$putVars = GetSanitizedPutVars();\n')
        outputFile.write ('\tif (')
        firstProp = True
        for prop in properties:
            if (prop.getAttribute ('type') != 'primary key'):
                continue
            propName = prop.getAttribute ('name')
            if (not firstProp):
                outputFile.write (' || ')
            outputFile.write ('!isset ($putVars["' + propName + '"])')
            firstProp = False
        outputFile.write ('){\n')
        outputFile.write ('\t\t$errorObject = new ApiErrorResponse ("Missing required parameters.");\n')
        outputFile.write ('\t\tprint (json_encode ($errorObject));\n')
        outputFile.write ('\t\texit();\n')
        outputFile.write ('\t}\n')
        outputFile.write ('\n\t$repo = Repositories::get' + cap(modelName) + 'Repository();\n')
        outputFile.write ('\tif (IsAuthorized() && IsCsrfGood()){\n')
        outputFile.write ('\t\t$' + modelName + ' = $repo->get' + cap(modelName) + 'ById($putVars["id"]);\n')
        outputFile.write ('\t\tprint ($' + modelName + '->toJson());\n')
        outputFile.write ('\t}\n')
        outputFile.write ('\telse{\n')
        outputFile.write ('\t\t$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");\n')
        outputFile.write ('\t\tprint (json_encode($errorObject));\n')
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
        
    
