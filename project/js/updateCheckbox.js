function updateRecord(tableName, recordId)
{
    var infoForUpd = [tableName, recordId];
    $.ajax({
                type: 'GET',
                dataType: 'text',
                url: 'checkBoxUpdate.php',
                data: {recordIndex: infoForUpd},
            });
}