<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('pollenwatcher');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType("pollenwatcher");
?>

<div class="row row-overflow">
    <div class="col-xs-12 eqLogicThumbnailDisplay">
		<legend><i class="fas fa-cog"></i> {{Gestion}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction logoPrimary" data-action="add">
				<i class="fas fa-plus-circle"></i>
				<br>
				<span>{{Ajouter}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
				<i class="fas fa-wrench"></i>
				<br>
				<span>{{Configuration}}</span>
			</div>
		</div>
        <legend><i class="fas fa-tree"></i> {{Mes Bulletins}}</legend>
        <!-- Champ de recherche -->
        <div class="input-group" style="margin:5px;">
            <input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchEqlogic" />
            <div class="input-group-btn">
                <a id="bt_resetSearch" class="btn roundedRight" style="width:30px"><i class="fas fa-times"></i></a>
            </div>
        </div>  
		<div class="eqLogicThumbnailContainer">

    <?php
    foreach ($eqLogics as $eqLogic) {
	  $opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
	  echo '<div class="eqLogicDisplayCard cursor ' . $opacity . '" data-eqLogic_id="' . $eqLogic->getId() . '">';
	  echo '<img src="' . $plugin->getPathImgIcon() . '"/>';
	  echo "<br>";
	  echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
	  echo '</div>';
      }
    ?>
		</div>
	</div>

	<div class="col-xs-12 eqLogic" style="display: none;">
    	<div class="input-group pull-right" style="display:inline-flex">
      		<span class="input-group-btn">
      			<a class="btn btn-sm btn-default eqLogicAction roundedLeft" data-action="configure"><i class="fas fa-cogs"></i><span class="hidden-xs"> {{Configuration avancée}}</span></a>
				<a class="btn btn-sm btn-default eqLogicAction" data-action="copy"><i class="fas fa-copy"></i><span class="hidden-xs"> {{Dupliquer}}</span>
      			<a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
  				<a class="btn btn-sm btn-danger eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
      		</span>
        </div>
		<ul class="nav nav-tabs" role="tablist">
    		<li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
    		<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
    		<li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Commandes}}</a></li>
  		</ul>
  		<div class="tab-content">
    		<div role="tabpanel" class="tab-pane active" id="eqlogictab">
     			<form class="form-horizontal">
        			<fieldset>
      					<div class="col-lg-7">
      						<legend><i class="fas fa-wrench"></i> {{Général}}</legend>
            				<div class="form-group">
                				<label class="col-sm-3 control-label">{{Nom de l'équipement}}</label>
                				<div class="col-sm-7">
                    				<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                    				<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement template}}"/>
                				</div>
            				</div>
            				<div class="form-group">
                				<label class="col-sm-3 control-label" >{{Objet parent}}</label>
                				<div class="col-sm-7">
                    				<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
                        				<option value="">{{Aucun}}</option>
                          				<?php
											$options = '';
                                        	foreach ((jeeObject::buildTree(null, false)) as $object) {
                                            	$options .= '<option value="' . $object->getId() . '">' . str_repeat('&nbsp;&nbsp;', $object->getConfiguration('parentNumber')) . $object->getName() . '</option>';
                                        	}
                                        	echo $options;
										?>
                   					</select>
               					</div>
           					</div>
	   						<div class="form-group">
                				<label class="col-sm-3 control-label">{{Catégorie}}</label>
                				<div class="col-sm-9">
                					<?php
                    				foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                    					echo '<label class="checkbox-inline">';
                    					echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                    					echo '</label>';
                    				}
                  					?>
               					</div>
          					</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Options}}</label>
								<div class="col-sm-9">
									<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" title="Activer l'équipement" checked />{{Activer}}</label>
									<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" title="Rendre l'équipement visible" checked />{{Visible}}</label>
								</div>
							</div>
        					</br>
        					<legend><i class="fas fa-cogs"></i> {{Paramètres}}</legend>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Region}}</label>
								<div class="col-sm-7">
									<select id="sel_region" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="region_id">					
									<option value="1">Ain</option>
									<option value="2">Aisne</option>
									<option value="3">Allier</option>
									<option value="4">Alpes-de-Haute-Provence</option>
									<option value="5">Hautes-Alpes</option>
									<option value="6">Alpes-Maritimes</option>
									<option value="7">Ardèche</option>
									<option value="8">Ardennes</option>
									<option value="9">Ariège</option>
									<option value="10">Aube</option>
									<option value="11">Aude</option>
									<option value="12">Aveyron</option>
									<option value="13">Bouches-du-Rhône</option>
									<option value="14">Calvados</option>
									<option value="15">Cantal</option>
									<option value="16">Charente</option>
									<option value="17">Charente-Maritime</option>
									<option value="18">Cher</option>
									<option value="19">Corrèze</option>
									<option value="21">Côte-d'or</option>
									<option value="22">Côtes-d'armor</option>
									<option value="23">Creuse</option>
									<option value="24">Dordogne</option>
									<option value="25">Doubs</option>
									<option value="26">Drôme</option>
									<option value="27">Eure</option>
									<option value="28">Eure-et-Loir</option>
									<option value="29">Finistère</option>
									<option value="20">Corse</option>
									<option value="30">Gard</option>
									<option value="31">Haute-Garonne</option>
									<option value="32">Gers</option>
									<option value="33">Gironde</option>
									<option value="34">Hérault</option>
									<option value="35">Ille-et-Vilaine</option>
									<option value="36">Indre</option>
									<option value="37">Indre-et-Loire</option>
									<option value="38">Isère</option>
									<option value="39">Jura</option>
									<option value="40">Landes</option>
									<option value="41">Loir-et-Cher</option>
									<option value="42">Loire</option>
									<option value="43">Haute-Loire</option>
									<option value="44">Loire-Atlantique</option>
									<option value="45">Loiret</option>
									<option value="46">Lot</option>
									<option value="47">Lot-et-Garonne</option>
									<option value="48">Lozère</option>
									<option value="49">Maine-et-Loire</option>
									<option value="50">Manche</option>
									<option value="51">Marne</option>
									<option value="52">Haute-Marne</option>
									<option value="53">Mayenne</option>
									<option value="54">Meurthe-et-Moselle</option>
									<option value="55">Meuse</option>
									<option value="56">Morbihan</option>
									<option value="57">Moselle</option>
									<option value="58">Nièvre</option>
									<option value="59">Nord</option>
									<option value="60">Oise</option>
									<option value="61">Orne</option>
									<option value="62">Pas-de-Calais</option>
									<option value="63">Puy-de-Dôme</option>
									<option value="64">Pyrénées-Atlantiques</option>
									<option value="65">Hautes-Pyrénées</option>
									<option value="66">Pyrénées-Orientales</option>
									<option value="67">Bas-Rhin</option>
									<option value="68">Haut-Rhin</option>
									<option value="69">Rhône</option>
									<option value="70">Haute-Saône</option>
									<option value="71">Saône-et-Loire</option>
									<option value="72">Sarthe</option>
									<option value="73">Savoie</option>
									<option value="74">Haute-Savoie</option>
									<option value="75">Paris</option>
									<option value="76">Seine-Maritime</option>
									<option value="77">Seine-et-Marne</option>
									<option value="78">Yvelines</option>
									<option value="79">Deux-Sèvres</option>
									<option value="80">Somme</option>
									<option value="81">Tarn</option>
									<option value="82">Tarn-et-Garonne</option>
									<option value="83">Var</option>
									<option value="84">Vaucluse</option>
									<option value="85">Vendée</option>
									<option value="86">Vienne</option>
									<option value="87">Haute-Vienne</option>
									<option value="88">Vosges</option>
									<option value="89">Yonne</option>
									<option value="90">Territoire de Belfort</option>
									<option value="91">Essonne</option>
									<option value="92">Hauts-de-Seine</option>
									<option value="93">Seine-Saint-Denis</option>
									<option value="94">Val-de-Marne</option>
									<option value="95">Val-d'oise</option>
									</select>
								</div>
            				</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Style affichage}}</label>
								<div class="col-sm-3">
									<select id="sel_object" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="global_style">					
										<!-- <option value="global_style_circle_thin">Cercle fin & texte blanc</option>
										<option value="global_style_circle_thin_color">Cercle fin & texte en couleur</option>
										<option value="global_style_circle_thin_color_white">Cercle fin blanc & texte blanc</option>-->
										<option value="global_style_circle_o">Cercle épais & texte blanc</option>
										<option value="global_style_circle_o_color">Cercle épais & texte en couleur</option>
										<option value="global_style_circle_o_color_white">Cercle épais blanc & texte blanc</option>
										<option value="global_style_circle_plain">Rond & texte blanc</option>
										<option value="global_style_circle_plain_only">Rond sans texte</option>
										<option value="none">sans</option>
									</select>
								</div>
							</div>
                     	</div>
                        <!-- Partie droite de l'onglet "Équipement" -->
                        <!-- Affiche l'icône du plugin par défaut mais vous pouvez y afficher les informations de votre choix -->
                        <div class="col-lg-5">
                            <legend><i class="fas fa-info"></i> {{Informations}}</legend>
                            <div class="form-group">
                                <div class="text-center">
                                    <img name="icon_visu" src="<?= $plugin->getPathImgIcon(); ?>" style="max-width:160px;" />
                                </div>
                            </div>
                        </div>
					</fieldset>
				</form>
			</div>
      		<div role="tabpanel" class="tab-pane" id="commandtab">
				<!-- <a class="btn btn-default btn-sm pull-right cmdAction" data-action="add" style="margin-top:5px;"><i class="fas fa-plus-circle"></i> {{Ajouter une info}}</a><br /><br />-->
				<div role="tabpanel" class="tab-pane active" id="commandtab">
					<table id="table_cmd" class="table table-bordered table-condensed ui-sortable">
    					<thead>
        					<tr>
            					<th style="width: 60px;">{{ID}}</th>
                                <th style="width: 150px;">{{Nom}}</th>
								<th style="width: 110px;">{{Type}}</th>
                                <th style="width: 110px;">{{Valeur}}</th>
                                <th style="width: 40px;">{{Min}}</th>
                                <th style="width: 40px;">{{Max}}</th>
								<th style="width: 300px;">{{Options}}</th>
                                <th style="width: 100px;"></th>
        					</tr>
    					</thead>
    					<tbody>
                            
    					</tbody>
					</table>
				</div>
			</div>
            

<?php include_file('desktop', 'pollenwatcher', 'js', 'pollenwatcher'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>