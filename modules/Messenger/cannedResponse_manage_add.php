<?php
/*
Gibbon, Flexible & Open School System
Copyright (C) 2010, Ross Parker

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

use Gibbon\Forms\Form;

if (isActionAccessible($guid, $connection2, '/modules/Messenger/cannedResponse_manage_add.php') == false) {
    //Acess denied
    echo "<div class='error'>";
    echo __($guid, 'You do not have access to this action.');
    echo '</div>';
} else {
    //Proceed!
    echo "<div class='trail'>";
    echo "<div class='trailHead'><a href='".$_SESSION[$guid]['absoluteURL']."'>".__($guid, 'Home')."</a> > <a href='".$_SESSION[$guid]['absoluteURL'].'/index.php?q=/modules/'.getModuleName($_GET['q']).'/'.getModuleEntry($_GET['q'], $connection2, $guid)."'>".__($guid, getModuleName($_GET['q']))."</a> > <a href='".$_SESSION[$guid]['absoluteURL']."/index.php?q=/modules/Messenger/cannedResponse_manage.php'>".__($guid, 'Manage Canned Responses')."</a> > </div><div class='trailEnd'>".__($guid, 'Add Canned Response').'</div>';
    echo '</div>';

    $editLink = '';
    if (isset($_GET['editID'])) {
        $editLink = $_SESSION[$guid]['absoluteURL'].'/index.php?q=/modules/Messenger/cannedResponse_manage_edit.php&gibbonMessengerCannedResponseID='.$_GET['editID'];
    }
    if (isset($_GET['return'])) {
        returnProcess($guid, $_GET['return'], $editLink, null);
	}
	
	$form = Form::create('canneResponse', $_SESSION[$guid]['absoluteURL'].'/modules/'.$_SESSION[$guid]['module'].'/cannedResponse_manage_addProcess.php');
                
	$form->addHiddenValue('address', $_SESSION[$guid]['address']);

	$row = $form->addRow();
		$row->addLabel('subject', __('Subject'))->description(__('Must be unique.'));
		$row->addTextField('subject')->isRequired()->maxLength(20);

	$row = $form->addRow();
		$col = $row->addColumn('body');
		$col->addLabel('body', __('Body'));
		$col->addEditor('body', $guid)->isRequired()->setRows(20)->showMedia(true);

	$row = $form->addRow();
		$row->addFooter();
		$row->addSubmit();

	echo $form->getOutput();
}
