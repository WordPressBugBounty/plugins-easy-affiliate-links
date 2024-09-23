var eafl_code_editor = eafl_code_editor || {};

eafl_code_editor.button = function(button, textarea) {
    var selection = eafl_code_editor.get_selection().text;

    EAFL_Modal.open('insert', {
        insertCallback: function(link, text) {
            var name = eafl_code_editor.shortcode_escape(link.name);
            text = eafl_code_editor.shortcode_escape(text);
            
            if ( ! text ) {
                text = 'affiliate link';
            }
            
            var shortcode = '[eafl id="' + link.id + '" name="' + name + '" text="' + text + '"]';
            eafl_code_editor.insertAtCaret( textarea, shortcode );
        },
        selectedText: selection,
    });
}

eafl_code_editor.shortcode_escape_map = {
    '"': "'"
};

eafl_code_editor.shortcode_escape = function(text) {
    return String(text).replace(/["]/g, function(s) {
        return eafl_code_editor.shortcode_escape_map[s];
    });
};

eafl_code_editor.get_selection = function() {
    var textComponent;
    textComponent = document.getElementById('replycontent');
    if (typeof textComponent == 'undefined' || !jQuery(textComponent).is(':visible')) // Not a comment reply
        textComponent = document.getElementById("content");

    var selectedText = {};

    if (parent.document.selection != undefined) { // IE
        textComponent.focus();
        var sel = parent.document.selection.createRange();
        selectedText.text = sel.text;
        selectedText.start = sel.start;
        selectedText.end = sel.end;
    } else if (textComponent && textComponent.selectionStart != undefined) { // Mozilla
        var startPos = textComponent.selectionStart;
        var endPos = textComponent.selectionEnd;
        selectedText.text = textComponent.value.substring(startPos, endPos)
        selectedText.start = startPos;
        selectedText.end = endPos;
    }

    return selectedText;
};

// Source: https://stackoverflow.com/questions/11076975/how-to-insert-text-into-the-textarea-at-the-current-cursor-position/55189998
eafl_code_editor.insertAtCaret = function (field, text) {
    text = text || '';
    if (document.selection) {
      // IE
      field.focus();
      var sel = document.selection.createRange();
      sel.text = text;
    } else if (field.selectionStart || field.selectionStart === 0) {
      // Others
      var startPos = field.selectionStart;
      var endPos = field.selectionEnd;
      field.value = field.value.substring(0, startPos) +
        text +
        field.value.substring(endPos, field.value.length);
        field.selectionStart = startPos + text.length;
        field.selectionEnd = startPos + text.length;
    } else {
        field.value += text;
    }
};

jQuery(document).ready(function($) {
    if (typeof QTags != 'undefined') {
        QTags.addButton('Easy_Affiliate_Link', 'easy affiliate link', eafl_code_editor.button, '', '', 'Easy Affiliate Link', 30);
    }
});