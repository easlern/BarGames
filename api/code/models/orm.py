from xml.dom import minidom
xmldoc = minidom.parse('models.xml')
models = xmldoc.getElementsByTagName('model')

for model in models:
    modelName = model.getAttribute('name')
    properties = model.getElementsByTagName('property')
    outputFile = open (modelName + 'Model.php', 'w')
    outputFile.write ('<?php\n\tclass ' + modelName + '{\n')
    for prop in properties:
        propertyName = prop.getAttribute('name')
        outputFile.write ('\t\tprivate $' + propertyName + ';\n')
    outputFile.write('\n')
    for prop in properties:
        propertyName = prop.getAttribute('name')
        outputFile.write ('\t\tpublic function get' + propertyName.capitalize() + '(){\n')
        outputFile.write ('\t\t\treturn $this->' + propertyName + ';\n')
        outputFile.write ('\t\t}\n')
    outputFile.write('\n')
    for prop in properties:
        propertyName = prop.getAttribute('name')
        outputFile.write ('\t\tpublic function set' + propertyName.capitalize() + '($value){\n')
        outputFile.write ('\t\t\t$this->' + propertyName + ' = $value;\n')
        outputFile.write ('\t\t}\n')
    outputFile.write ('\t}\n?>\n')
    outputFile.close()
