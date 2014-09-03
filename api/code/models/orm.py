from xml.dom import minidom
xmldoc = minidom.parse('models.xml')
models = xmldoc.getElementsByTagName('model')

def makeObjects():
    for model in models:
        modelName = model.getAttribute('name')
        properties = model.getElementsByTagName('property')
        outputFile = open (modelName + 'Model.php', 'w')
        outputFile.write ('<?php\n\tclass ' + modelName.capitalize() + '{\n')
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
            outputFile.write ('\t\tpublic function get' + propertyName.capitalize() + '(){\n')
            outputFile.write ('\t\t\treturn $this->' + propertyName + ';\n')
            outputFile.write ('\t\t}\n')
        outputFile.write('\n')
        for prop in properties:
            if (prop.getAttribute ('type') == 'primary key'):
                continue
            propertyName = prop.getAttribute('name')
            outputFile.write ('\t\tpublic function set' + propertyName.capitalize() + '($value){\n')
            outputFile.write ('\t\t\t$this->' + propertyName + ' = $value;\n')
            outputFile.write ('\t\t}\n')
        outputFile.write ('\t}\n?>\n')
        outputFile.close()

def makeWebAccessors():
    for model in models:
        modelName = model.getAttribute('name')
        outputFile = open ('get' + modelName.capitalize() + 'ById.php', 'w')
        outputFile.write ('<?php\n\n') 
        outputFile.write ('\trequire_once (\'' + modelName + 'Model.php\');\n') # is this needed when autoload is in play?
        outputFile.write ('\trequire_once (\'restfulSetup.php\');\n')
        outputFile.write ('\trequire_once (\'repositories.php\');\n') # is this needed when autoload is in play?
        outputFile.write ('\n\t$repo = Repositories::get' + modelName.capitalize() + 'Repository();\n')
        outputFile.write ('\tif (IsAuthorized() && IsCsrfGood()){\n')
        outputFile.write ('\t\t$' + modelName + ' = $repo->getById(GetSanitizedPostVars()["id"]);\n')
        outputFile.write ('\t\tprint (json_encode ($' + modelName + '));\n')
        outputFile.write ('\t}\n')
        outputFile.write ('\telse{\n')
        outputFile.write ('\t\t$errorObject = new ApiErrorResponse("Not authenticated or CSRF token is invalid.");\n')
        outputFile.write ('\t\tprint (json_encode ($errorObject));\n')
        outputFile.write ('\t}\n')
        outputFile.write ('\n?>\n')
        outputFile.close()

def makeRepositories():
    outputFile = open ('repositories.php', 'w')
    outputFile.write ('<?php\n\n')
    for model in models:
        modelName = model.getAttribute ('name')
        properties = model.getElementsByTagName ('property')
        outputFile.write ('\tclass Test' + modelName.capitalize() + 'Repository{\n')
        outputFile.write ('\t\tpublic get' + modelName.capitalize() + 'ById ($id){\n')
        outputFile.write ('\t\t\treturn new ' + modelName.capitalize() + '($id')
        for prop in properties:
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
        outputFile.write ('\t\tpublic static function get' + modelName.capitalize() + 'Repository(){\n')
        outputFile.write ('\t\t\treturn new Test' + modelName.capitalize() + 'Repository();\n')
        outputFile.write ('\t\t}\n')
    outputFile.write ('\t}\n')
    outputFile.write ('\n?>\n')
    outputFile.close()


makeObjects()
makeWebAccessors()
makeRepositories()
        
    
