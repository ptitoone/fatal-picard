// SHIP OBJECT DECLARATION //

var smallcargo = {name: 'smallcargo', metal: 2000, crystal: 2000, deuterium: 0, metaltot: 0, crystaltot: 0, deuteriumtot: 0};
var largecargo = {name: 'largecargo', metal: 6000, crystal: 6000, deuterium: 0, metaltot: 0, crystaltot: 0, deuteriumtot: 0};
var lightfighter = {name: 'lightfighter', metal: 3000, crystal: 1000, deuterium: 0, metaltot: 0, crystaltot: 0, deuteriumtot: 0};
var heavyfighter = {name: 'heavyfighter', metal: 6000, crystal: 4000, deuterium: 0, metaltot: 0, crystaltot: 0, deuteriumtot: 0};
var cruiser = {name: 'cruiser', metal: 20000, crystal: 7000, deuterium: 2000, metaltot: 0, crystaltot: 0, deuteriumtot: 0};
var battleship = {name: 'battleship', metal: 45000, crystal: 15000, deuterium: 0, metaltot: 0, crystaltot: 0, deuteriumtot: 0};
var colonyship = {name: 'colonyship', metal: 10000, crystal: 20000, deuterium: 10000, metaltot: 0, crystaltot: 0, deuteriumtot: 0};
var recycler = {name: 'recycler', metal: 10000, crystal: 6000, deuterium: 2000, metaltot: 0, crystaltot: 0, deuteriumtot: 0};
var probe = {name: 'probe', metal: 0, crystal: 1000, deuterium: 0, metaltot: 0, crystaltot: 0, deuteriumtot: 0};
var bomber = {name: 'bomber', metal: 50000, crystal: 25000, deuterium: 15000, metaltot: 0, crystaltot: 0, deuteriumtot: 0};
var destroyer = {name: 'destroyer', metal: 60000, crystal: 50000, deuterium: 15000, metaltot: 0, crystaltot: 0, deuteriumtot: 0};
var deathstar = {name: 'deathstar', metal: 5000000, crystal: 4000000, deuterium: 1000000, metaltot: 0, crystaltot: 0, deuteriumtot: 0};
var battlecruiser = {name: 'battlecruiser', metal: 30000, crystal: 40000, deuterium: 15000, metaltot: 0, crystaltot: 0, deuteriumtot: 0};
var megacargo = {name: 'megacargo', metal: 30000, crystal: 70000, deuterium: 25000, metaltot: 0, crystaltot: 0, deuteriumtot: 0};
var xp = {name: 'xp', metal: 15000, crystal: 25000, deuterium: 5000, metaltot: 0, crystaltot: 0, deuteriumtot: 0};


// RESSOURCES OBJECT DECLARATION //

var recycle = {metal: 0, crystal: 0};
var ressources = {metal: 0, crystal: 0, deuterium: 0};
var deuteriumSpent = 0;
var totalCosts = {totalmetal: 0, totalcrystal: 0, totaldeuterium: 0};
var endup = {metal: 0, crystal: 0};

// INPUTS AND DATA RECEIVER TAGS INITIALISATION //

var init = document.getElementsByTagName("input");
for (i = 0 ; i < init.length ; i++ ){ 
    init[i].value = "";
}

document.getElementById('reportmetal').innerText = 0;
document.getElementById('reportcrystal').innerText = 0;
document.getElementById('reportdeuterium').innerText = 0;
document.getElementById('reportrecyclers').innerText = 0;
document.getElementById('reportmegacarg').innerText = 0;

// FUNCTIONS RESSOUCES INPUT //

function recycleLog(){
        
        recycle['metal'] = Number(document.getElementById('inputmetal').value);
        recycle['crystal'] = Number(document.getElementById('inputcrystal').value);
    
        totalCalc();
        report();

}

function ressourcesLog(){

        ressources['metal'] = Number(document.getElementById('inputmetalattack').value);
        ressources['crystal'] = Number(document.getElementById('inputcrystalattack').value);
        ressources['deuterium'] = Number(document.getElementById('inputdeuteriumattack').value); 
        
        totalCalc();
        report();

}

function deuteriumSpentLog(){
    
        deuteriumSpent = Number(document.getElementById('deuteriumspent').value);
    
        totalCalc();
        report();

}


// FUNCTION SHIP COST //

function calcs(mainShipName){
    var shipName = mainShipName;
    
    function remainCalc(shipName){
        
        var valeur1 = Number(document.getElementById(shipName).getElementsByClassName('count-before')[0].getElementsByTagName('input')[0].value);
        var valeur2 = Number(document.getElementById(shipName).getElementsByClassName('count-after')[0].getElementsByTagName('input')[0].value);
        
                
                if(isNaN(valeur1) || isNaN(valeur2)){
                    document.getElementById(shipName).getElementsByClassName('remaining')[0].firstChild.innerText = 0;
                }else if(valeur1 <= 0 || valeur2 < 0){
                    document.getElementById(shipName).getElementsByClassName('remaining')[0].firstChild.innerText = 0;                
                }else if(valeur1 <= valeur2){
                    document.getElementById(shipName).getElementsByClassName('remaining')[0].firstChild.innerText = 0;
                }else if(valeur2 == 0){
                    document.getElementById(shipName).getElementsByClassName('remaining')[0].firstChild.innerText = valeur1;
                }else{
                  var result = valeur1 -= valeur2;
                    document.getElementById(shipName).getElementsByClassName('remaining')[0].firstChild.innerText = result;
                }
    }

                
    function costCalc(shipName){
        
        var multiplier = Number(document.getElementById(shipName).getElementsByClassName('remaining')[0].firstChild.innerText);
        this[shipName].metaltot  = 0;
        this[shipName].crystaltot = 0;
        this[shipName].deuteriumtot = 0;
        
        if(multiplier == 0){ 
            document.getElementById(shipName).getElementsByClassName('cost-metal')[0].firstChild.innerText = 0;
            document.getElementById(shipName).getElementsByClassName('cost-crystal')[0].firstChild.innerText = 0;
            document.getElementById(shipName).getElementsByClassName('cost-deuterium')[0].firstChild.innerText = 0;
        }else{
            this[shipName].metaltot  = this[shipName].metal * multiplier;
            this[shipName].crystaltot = this[shipName].crystal * multiplier;
            this[shipName].deuteriumtot = this[shipName].deuterium * multiplier; 
            
            document.getElementById(shipName).getElementsByClassName('cost-metal')[0].firstChild.innerText = formatNumber(this[shipName].metaltot);
            document.getElementById(shipName).getElementsByClassName('cost-crystal')[0].firstChild.innerText = formatNumber(this[shipName].crystaltot);
            document.getElementById(shipName).getElementsByClassName('cost-deuterium')[0].firstChild.innerText = formatNumber(this[shipName].deuteriumtot);
        }
        
    }

    remainCalc(shipName);
    costCalc(shipName);
    totalCalc();
    report();
   
    
}


// FUNCTION TOTAL COSTS //

function totalCalc(){
        var metal = 0;
        var crystal = 0;
        var deuterium = 0;
        
       metal =      smallcargo['metaltot'] +
                    largecargo['metaltot'] +
                    lightfighter['metaltot'] +
                    heavyfighter['metaltot'] +
                    cruiser['metaltot'] +
                    battleship['metaltot'] +
                    colonyship['metaltot'] +
                    recycler['metaltot'] +
                    probe['metaltot'] +
                    bomber['metaltot'] +
                    destroyer['metaltot'] +
                    deathstar['metaltot'] +
                    battlecruiser['metaltot'] +
                    megacargo['metaltot'] +
                    xp['metaltot'];
        
       crystal =    smallcargo['crystaltot'] +
                    largecargo['crystaltot'] +
                    lightfighter['crystaltot'] +
                    heavyfighter['crystaltot'] +
                    cruiser['crystaltot'] +
                    battleship['crystaltot'] +
                    colonyship['crystaltot'] +
                    recycler['crystaltot'] +
                    probe['crystaltot'] +
                    bomber['crystaltot'] +
                    destroyer['crystaltot'] +
                    deathstar['crystaltot'] +
                    battlecruiser['crystaltot'] +
                    megacargo['crystaltot'] +
                    xp['crystaltot'];
        
        deuterium = smallcargo['deuteriumtot'] +
                    largecargo['deuteriumtot'] +
                    lightfighter['deuteriumtot'] +
                    heavyfighter['deuteriumtot'] +
                    cruiser['deuteriumtot'] +
                    battleship['deuteriumtot'] +
                    colonyship['deuteriumtot'] +
                    recycler['deuteriumtot'] +
                    probe['deuteriumtot'] +
                    bomber['deuteriumtot'] +
                    destroyer['deuteriumtot'] +
                    deathstar['deuteriumtot'] +
                    battlecruiser['deuteriumtot'] +
                    megacargo['deuteriumtot'] +
                    xp['deuteriumtot'];
        
        totalCosts['totalmetal'] = metal;
        totalCosts['totalcrystal'] = crystal;
        totalCosts['totaldeuterium'] = deuterium;
                    
        document.getElementById('total').getElementsByClassName('cost-metal')[0].firstChild.innerText = formatNumber(totalCosts['totalmetal']);
        document.getElementById('total').getElementsByClassName('cost-crystal')[0].firstChild.innerText = formatNumber(totalCosts['totalcrystal']);
        document.getElementById('total').getElementsByClassName('cost-deuterium')[0].firstChild.innerText = formatNumber(totalCosts['totaldeuterium']);        
}


// FUNCTION REPORT //

function report(){
        
        var metal = 0;
        var crystal = 0;
        var deuterium = 0;
        var totalRessources = 0;
        var debris = 0;
        var recyclers = 0;
        var megacargCount = 0;
            
        metal = ressources['metal'] + recycle['metal'];
        crystal = ressources['crystal'] + recycle['crystal'];
        deuterium = ressources['deuterium'] - deuteriumSpent;
    
        debris = recycle['metal'] + recycle['crystal'];
        recyclers = debris / 20000;
    
        totalRessources = ressources['metal'] + ressources['crystal'] + ressources['deuterium'];
        megacargCount = totalRessources / 250000;
        
        endup['metal'] = metal - totalCosts['totalmetal'];
        endup['crystal'] = crystal - totalCosts['totalcrystal'];
        endup['deuterium'] = deuterium - totalCosts['totaldeuterium'];
    
//    if(ressources['deuterium'] == 0) {endup['deuterium'] = 0}
    
    
    
        document.getElementById('reportmetal').innerText =  formatNumber(endup['metal']);
        document.getElementById('reportcrystal').innerText = formatNumber(endup['crystal']);
        document.getElementById('reportdeuterium').innerText = formatNumber(endup['deuterium']);
        document.getElementById('reportrecyclers').innerText = formatNumber(Math.ceil(recyclers));
        document.getElementById('reportmegacarg').innerText = Math.ceil(megacargCount);
    
    
        
}


// FUNCTION FORMAT NUMBER // 

function formatNumber(num) {
    if(isNaN(num) || typeof num === 'undefined'){ return num = 0;}
    else{
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    }
        
    }


