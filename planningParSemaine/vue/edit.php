	<script>			
		//===== Gestion de la mise à jour.
		document.getElementById("commitBtn").addEventListener('click', normalCommit, false);
		
		var newcourses = { "newcourses":[]};
		var groupedcourses = { "groupedcourses":[]};
		var authorization = false;
		
		function handleCommit(e) {
			//console.log("time to commit");
			//send Asynchronous HTTP request to server to commit changes 
			
			$.ajax({
				url : "task.php",
				type : 'POST',
				data : newcourses,
				dataType : 'json', // On désire recevoir du HTML
				success : function(JsonResponse, statut){ 
					console.log("Success");
					 newcourses = {"newcourses":[]};					
				}
			});
		}
		
		function normalCommit(e) {
			handleCommit(e);
			bootbox.alert("Commit reussi avec succes", function() {});
		}
		
		function autoCommit(e) {
			handleCommit(e);
			var iDiv = document.createElement('div');
			iDiv.id = 'idiv';
			iDiv.className = 'alert alert-success alert-dismissible';
			iDiv.innerHTML="Auto Commit effectue avec succes";
			var body = document.getElementsByTagName('body');
			var body = body[0];
			body.insertBefore(iDiv, body.firstChild);
			setTimeout(function(){ $('#idiv').remove(); }, 30000);
		}
			
		//===== Gestion du glisser-deposer
		
		var dragSrcEl = null;

		function handleDragStart(e) {
			this.style.opacity = '0.4';  // this / e.target is the source node.
			dragSrcEl = this;

			e.dataTransfer.effectAllowed = 'move';
			e.dataTransfer.setData('text/plain', this.id);
		}
		
		function handleDragOver(e) {
			if (e.preventDefault) {
				e.preventDefault(); // Necessary. Allows us to drop.
			}
				
			e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.

			return false;
		}

		function handleDragEnter(e) {
		// this / e.target is the current hover target.
			this.classList.add('over');	
		}

		function handleDragLeave(e) {
			this.classList.remove('over');  // this / e.target is previous target element.
		}
		
		function handleDrop(e) {
			// this / e.target is current target element.
			 if(e.preventDefault) {
			 e.preventDefault(); 
			 }
				
			if (e.stopPropagation) {
			e.stopPropagation(); // stops the browser from redirecting.
			}
	
			var sourceElemIdSplitted = dragSrcEl.id.split("_");
			var sourceElemId = sourceElemIdSplitted[0]+"_"+sourceElemIdSplitted[1]+"_"+sourceElemIdSplitted[2];
			var targetElemId = this.id.substring(0,sourceElemId.length);
			if(sourceElemId==targetElemId){ // Comparaison entre l'id du pave et l'id de la case semaine (splité)
				var idSplitted = this.id.split("_");
				var dragSrcElSplitted = dragSrcEl.id.split("_");
				var newweek = idSplitted[3]; //variable contenant la nouvelle semaine pour le pave
				var maxhours = document.getElementById("HM_"+newweek).innerHTML; //variable contenant l'heure max de la semaine
				maxhours = maxhours.substring(0,maxhours.length - 1);
				
				var affectedhours = document.getElementById("HA_"+newweek).innerHTML; //variable contenant les heures affectés de la semaine
				affectedhours = affectedhours.substring(0,affectedhours.length - 1);
				affectedhours = parseInt(affectedhours);
				
				var freehours = maxhours - affectedhours; // différence entre l'heure max et l'heure affecté => heure libre
				
				var module = dragSrcElSplitted[0]; //variable contenant le module du pave
				var partie = dragSrcElSplitted[2]; //variable contenant la partie (type) du pave
				var groupe = dragSrcElSplitted[4]; //variable contenant le groupe du pave (si c'est commun avec d'autre promo ou non)
				var hours =  dragSrcElSplitted[3];
				console.log(hours);
				if (hours.indexOf ("item")>-1){   // Si le pavé a pour durée 2 h
					var hours = 2;
				}
				else if (hours.indexOf ("half")>-1){ // Si le pavé a pour durée 1 h
					var hours = 1;
				}
				
				var parentNode = dragSrcEl.parentNode;
				var oldIdsplitted = parentNode.id.split("_");
				var parentNode = dragSrcEl.parentNode;
				
				if(oldIdsplitted.length == 4){ 
					var oldweek = oldIdsplitted[3];	//variable contenant l'ancienne semaine du pave
				}
				else {
					var oldweek = null;
				}
				
				if(groupe == "NORMAL" || (groupe == "GROUPE" && partie.indexOf("TD")>-1)){ // Si c'est un pave normal ou un pavé groupé TD
					if(hours <= freehours) { // Si Heures Libre 
						var ref = parentNode.removeChild(dragSrcEl);
						this.appendChild(ref);
						if(oldweek !=null){
							updateTauxRemplissage(-hours,oldweek);
						}
						updateTauxRemplissage(hours,newweek);
						updateCourses(parentNode.id,this.id,dragSrcEl.id);
						}
					else{
						bootbox.alert("Semaine pleine ou presque pleine", function() {});
					}
				}
				else if(groupe == "GROUPE" && !(partie.indexOf("TD")>-1)){
					var temp =this;
					groupedcourses["groupedcourses"].push(
					{"week":newweek, "module":module,"hours":hours }
				);
					console.log(groupedcourses);
					$.ajax({
						url : "grouptask.php",
						type : 'POST',
						data : groupedcourses,
						dataType : 'json', // On désire recevoir du HTML
						success : function(JsonResponse, statut){ 
							 console.log("ok"+JsonResponse.authorization+freehours);
							 if(authorization = JsonResponse.authorization){
								var ref = parentNode.removeChild(dragSrcEl);
								temp.appendChild(ref);
								if(oldweek !=null){
									updateTauxRemplissage(-hours,oldweek);
								}
								updateTauxRemplissage(hours,newweek);
								updateCourses(parentNode.id,temp.id,dragSrcEl.id);
								}
							else{
								bootbox.alert("Semaine pleine pour les autres promos", function() {});
							}
							 groupedcourses = {"groupedcourses":[]};
							
						}
					});			
				
				}

			}
			return false;
		}
		
		// Modification du Taux de Remplissage
		function updateTauxRemplissage(hours,week){
			var maxhours = document.getElementById("HM_"+week).innerHTML;
			maxhours = maxhours.substring(0,maxhours.length - 1);
			var affectedhours = document.getElementById("HA_"+week).innerHTML;
			affectedhours = affectedhours.substring(0,affectedhours.length - 1);
			affectedhours = parseInt(affectedhours);
			affectedhours = affectedhours + hours;
			document.getElementById("HA_"+week).innerHTML=affectedhours+'h';
			var tauxremplissage = (affectedhours/maxhours) * 100;
			document.getElementById("TR_"+week).innerHTML = tauxremplissage+'%';
		}
		
		//Traitement des modifications pour le pave à deplacer
		function updateCourses(oldId,newId,itemId) {
				var oldIdsplitted = oldId.split("_");
				if(oldIdsplitted.length == 4){
					var oldweek = oldIdsplitted[3];
				}
				else {
					var oldweek = null;
				}
					
				var newIdsplitted = newId.split("_");
				var newweek = newIdsplitted[3];
				var itemIdsplitted = itemId.split("_");
				var module = itemIdsplitted[0];
				var enseignant = itemIdsplitted[1];
				var partie = itemIdsplitted[2];
				if (itemIdsplitted[3].indexOf("item")>-1){
					var hours = 2;
				}
				else if (itemIdsplitted[3].indexOf("half")>-1){
					var hours = 1;
				}
				if(oldweek!=null){
					addCourses(module,enseignant,partie,oldweek,-hours);
				}
				addCourses(module,enseignant,partie,newweek,hours);
				console.log(newcourses);
		}
		
		//Fonction qui ajoute les differents déplacement de pavé dans la variable newcourses avant l'envoi de la requete ajax
		function addCourses(module,enseignant,partie,week,hours) {
			if(newcourses["newcourses"].length != 0){
				for(x in newcourses["newcourses"]){
					var course = newcourses["newcourses"][x];
					if(course["week"]==week && course["module"]==module && course["enseignant"]==enseignant && course["partie"]==partie){
							course["hours"]+=hours;
							return 0;
						}	
				}
				
			}			
			newcourses["newcourses"].push(
					{"week":week, "module":module , "enseignant":enseignant, "partie":partie, "hours":hours }
				);
		
		}

		function handleDragEnd(e) {
		// this/e.target is the source node.

			[].forEach.call(weeks, function (week) {
				week.classList.remove('over');
			});
		}
		
		function repositionCourse(e) {
			var parent = this.parentNode;
			 var sourceElemIdSplitted = this.id.split("_");
			 var sourceElemId = sourceElemIdSplitted[0]+"_"+sourceElemIdSplitted[1]+"_"+sourceElemIdSplitted[2];
			 if(parent.className!="quantity"){
				var newparent = document.getElementById(sourceElemId);
				var newIdsplitted = this.id.split("_");
				var Idsplitted = parent.id.split("_");
				var newweek = Idsplitted[3];
				var module = newIdsplitted[0];
				var enseignant = newIdsplitted[1];
				var partie = newIdsplitted[2];
				if (newIdsplitted[3].indexOf ("item")>-1){
					var hours = 2;
				}
				else if (newIdsplitted[3].indexOf ("half")>-1){
					var hours = 1;
				}
				addCourses(module,enseignant,partie,newweek,-hours);
				updateTauxRemplissage(-hours,newweek);
				parent.removeChild(this);
				newparent.appendChild(this);
				
			}			
		}

			
		var courses = document.querySelectorAll('.course');
		[].forEach.call(courses, function(course) {
			course.addEventListener('dragstart', handleDragStart, false);
			course.addEventListener('dragend', handleDragEnd, false);
			course.addEventListener("dblclick", repositionCourse, false);
		});
		
		var weeks = document.querySelectorAll('.week');
		[].forEach.call(weeks, function(week) {
			week.addEventListener('dragenter', handleDragEnter, false);
			week.addEventListener('dragover', handleDragOver, false);
			week.addEventListener('dragleave', handleDragLeave, false);
			week.addEventListener('drop', handleDrop, false);
		});
		

		setInterval(autoCommit, 90000);
		
    </script>
