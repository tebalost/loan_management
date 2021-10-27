
function check() 
{      
    var inputLoanDurationPeriod = document.getElementById("inputLoanDurationPeriod");
    var loan_interest_period_value = document.getElementById("inputInterestPeriod").value;
    var loan_duration_period_value = "";

    if (loan_interest_period_value == "Day")
        loan_duration_period_value = "Days";
        
    else if (loan_interest_period_value == "Week")
        loan_duration_period_value = "Weeks";
        
    else if (loan_interest_period_value == "Month")
        loan_duration_period_value = "Months";
    
    else if (loan_interest_period_value == "Year")
        loan_duration_period_value = "Years";

    selectItemByValue(inputLoanDurationPeriod, loan_duration_period_value);
} 


function selectItemByValue(elmnt, value)
{
    for(var i=0; i < elmnt.options.length; i++)
    {
        if(elmnt.options[i].value == value)
            elmnt.selectedIndex = i;
    }
}
function setNumofRep() 
{
     var inputLoanDuration = document.getElementById("inputLoanDuration").value;
     var inputLoanDurationPeriod = document.getElementById("inputLoanDurationPeriod").value;
     var inputLoanPaymentSchemeId = document.getElementById("inputLoanPaymentSchemeId");
     var inputLoanPaymentSchemeIdText = inputLoanPaymentSchemeId.options[inputLoanPaymentSchemeId.selectedIndex].text;
     var inputLoanNumOfRepayments = document.getElementById("inputLoanNumOfRepayments");
     
     if (inputLoanDurationPeriod != "")
     {
	     var totalRepayments = 0;
	     var yearly = 0;
	     var monthly = 0;
	     var weekly = 0;
	     var daily = 0;
	    
	     if (inputLoanPaymentSchemeIdText == "Daily") 
	     {
	         yearly = 360;
	         monthly = 30;
	         biweekly = 14;
	         weekly = 7;
	         daily = 1;
	     }  
	     else if (inputLoanPaymentSchemeIdText == "Weekly") 
	     {
	         yearly = 52;
	         monthly = 4;
	         biweekly = 2;
	         weekly = 1;
	         daily = 1/7;
	     }  
	     else if (inputLoanPaymentSchemeIdText == "Biweekly") 
	     {
	         yearly = 26;
	         monthly = 2;
	         biweekly = 1;
	         weekly = 1/2;
	         daily = 1/14;
	     }  
	     else if (inputLoanPaymentSchemeIdText == "Monthly") 
	     {
	         yearly = 12;
	         monthly = 1;
	         biweekly = 1/2;
	         weekly = 1/4;
	         daily = 1/30;
	     }
	     else if (inputLoanPaymentSchemeIdText == "Bimonthly") 
	     {
	         yearly = 6;
	         monthly = 1/2;
	         biweekly = 1/4;
	         weekly = 1/8;
	         daily = 1/60;
	     }
	     else if (inputLoanPaymentSchemeIdText == "Quarterly") 
	     {
	         yearly = 4;
	         monthly = 1/3;
	         biweekly = 1/6;
	         weekly = 1/12;
	         daily = 1/90;
	     }
	     else if (inputLoanPaymentSchemeIdText == "Every 4 Months") 
	     {
	         yearly = 3;
	         monthly = 1/4;
	         biweekly = 1/8;
	         weekly = 1/16;
	         daily = 1/120;
	     }
	     else if (inputLoanPaymentSchemeIdText == "Semi-Annual") 
	     {
	         yearly = 2;
	         monthly = 1/6;
	         biweekly = 1/12;
	         weekly = 1/24;
	         daily = 1/180;
	     }    
	     else if (inputLoanPaymentSchemeIdText == "Yearly") 
	     {
	         yearly = 1;
	         monthly = 1/12;
	         biweekly = 1/24;
	         weekly = 1/38;
	         daily = 1/360;
	     } 
	     else
	     {
	     	if (inputLoanPaymentSchemeIdText != '')
	     	{
		     	var res = inputLoanPaymentSchemeIdText.split("-");
		     	if (res[1] == 'days')
		     	{
		     		yearly = 360/res[0];
		      		monthly = 30/res[0];
	         		biweekly = 14/res[0];
		       		weekly = 7/res[0];
		   			daily = 1/res[0];
		   		}
		   		else if (res[1] != '')
		   		{
		   			var res_count = res.length; 
		   			
		     		yearly = 12*res_count;
		      		monthly = res_count;
		       		biweekly = 8/res[0];
		       		weekly = 4/res[0];
		   			daily = 1/res[0];
		   		}
			}
	   		else
	   		{
	     		yearly = 1;
	      		monthly = 1;
	       		weekly = 1;
	   			daily = 1;
	   		}
	     }
	     
	     if (inputLoanDurationPeriod == "Days") 
	     {
	        totalRepayments = inputLoanDuration * daily;
	     }
	     if (inputLoanDurationPeriod == "Weeks") 
	     {
	        totalRepayments = inputLoanDuration * weekly;
	     }
	     if (inputLoanDurationPeriod == "Months") 
	     {
	        totalRepayments = inputLoanDuration * monthly;
	     }
	     if (inputLoanDurationPeriod == "Years") 
	     {
	        totalRepayments = inputLoanDuration * yearly;
	     }
	     totalRepayments = Math.floor(totalRepayments);
	     
	     if (inputLoanPaymentSchemeIdText == "Lump-Sum") 
	     	totalRepayments = 1;
	     	
	     if (totalRepayments > 0)
	        inputLoanNumOfRepayments.value =  totalRepayments;
	     
	     if (inputLoanPaymentSchemeIdText != "")   
	     	$("#inputLoanNumOfRepaymentsChanged").html("<div class=\"form-control bg-red\">&larr; Updated!</div>");
	}
} 

function removeNumRepaymentsMessage()
{
    $("#inputLoanNumOfRepaymentsChanged").html("");
}    

function disableNumRepayments()
{
	var inputLoanNumOfRepayments = document.getElementById("inputLoanNumOfRepayments");
    var inputLoanPaymentSchemeId = document.getElementById("inputLoanPaymentSchemeId");
    var inputLoanPaymentSchemeIdText = inputLoanPaymentSchemeId.options[inputLoanPaymentSchemeId.selectedIndex].text;
    if  (inputLoanPaymentSchemeIdText == "Lump-Sum")
    {
        inputLoanNumOfRepayments.value =  1;
    }
}

function first_repayment_pro_rata_click()
{
	var LoanFirstRepaymentAmountProRata = document.getElementById("LoanFirstRepaymentAmountProRata");
	var inputLoanDoNotAdjustRemainingProRata = document.getElementById("inputLoanDoNotAdjustRemainingProRata");
	var inputLoanFeesProRata = document.getElementById("inputLoanFeesProRata");
	var inputLoanInterestMethod = document.getElementById("inputLoanInterestMethod").value;
	if (LoanFirstRepaymentAmountProRata.checked)
	{
		$("#inputFirstRepaymentAmount").prop('disabled', true);
		$("#inputFirstRepaymentAmount").val('');
		
		$("#inputLoanFeesProRata").prop('disabled', false);
		
		if ((inputLoanInterestMethod == "flat_rate") || (inputLoanInterestMethod == "interest_only"))
		{
			$("#inputLoanDoNotAdjustRemainingProRata").prop('disabled', false);
		}
	}
	else
	{
		$("#inputFirstRepaymentAmount").prop('disabled', false);
		
		$("#inputLoanDoNotAdjustRemainingProRata").prop('checked', false);
		$("#inputLoanDoNotAdjustRemainingProRata").prop('disabled', true);
		
		$("#inputLoanFeesProRata").prop('checked', false);
		$("#inputLoanFeesProRata").prop('disabled', true);
	}
}

function enableNumRepayments()
{
	$("#inputLoanNumOfRepayments").removeAttr("disabled");
}

function enableDisableMethod()
{
	var inputLoanInterestMethod = document.getElementById("inputLoanInterestMethod").value;
    var inputLoanPaymentSchemeId = document.getElementById("inputLoanPaymentSchemeId");

    if  (inputLoanInterestMethod == "flat_rate")
    {
        $("#inputFirstRepaymentAmount").prop('disabled', false);
        $("#inputLastRepaymentAmount").prop('disabled', false);
        
        var LoanFirstRepaymentAmountProRata = document.getElementById("LoanFirstRepaymentAmountProRata");
        if (LoanFirstRepaymentAmountProRata.checked)
        {
            $("#inputLoanFeesProRata").prop('disabled', false);
        	$("#inputLoanDoNotAdjustRemainingProRata").prop('disabled', false);
  		}
    }
    else
    {
    	$("#inputFirstRepaymentAmount").prop('disabled', true);
    	$("#inputLastRepaymentAmount").prop('disabled', true);
        if (inputLoanInterestMethod == "interest_only")
        {
        	var LoanFirstRepaymentAmountProRata = document.getElementById("LoanFirstRepaymentAmountProRata");
        	if (LoanFirstRepaymentAmountProRata.checked)
        	{
        	    $("#inputLoanFeesProRata").prop('disabled', false);
        		$("#inputLoanDoNotAdjustRemainingProRata").prop('disabled', false);
        	}
        }
        else
       	{
        	$("#inputLoanDoNotAdjustRemainingProRata").prop('checked', false);
        	$("#inputLoanDoNotAdjustRemainingProRata").prop('disabled', true);
       	}
  	}
  	
  	var inputLoanPaymentSchemeId = document.getElementById("inputLoanPaymentSchemeId");
    
    for (i = 0; i < inputLoanPaymentSchemeId.length; i++) {
        var repayment = inputLoanPaymentSchemeId.options[i].text;
        if  (((inputLoanInterestMethod != "flat_rate")   && (inputLoanInterestMethod != "interest_only") && (inputLoanInterestMethod != "compound_interest")) && (repayment == "Lump-Sum"))
        {
            inputLoanPaymentSchemeId.options[i].disabled = true;
            inputLoanPaymentSchemeId.options[i].selected = false;
        }else
        {
            inputLoanPaymentSchemeId.options[i].disabled = false;
        }
    }
    var inputLoanInterestMethod = document.getElementById("inputLoanInterestMethod").value;
    if ((inputLoanInterestMethod == "flat_rate") || (inputLoanInterestMethod == "interest_only"))
    {
        document.getElementById("inputInterestTypeFixed").disabled = false;
    }
    else
    {
        document.getElementById("inputInterestTypeFixed").disabled = true;
        document.getElementById("inputInterestTypePercentage").checked = true;
    }
    checkITPRRadio();
}
function checkITPRRadio() 
{
    var inputLoanInterestLabel = document.getElementById("inputLoanInterestLabel");
    var inputLoanInterest = document.getElementById("inputLoanInterest");
    if (document.getElementById("inputInterestTypePercentage").checked)
    {
        inputLoanInterestLabel.innerHTML = "Loan Interest %";
        inputLoanInterest.placeholder = "%";
    }
    else if (document.getElementById("inputInterestTypeFixed").checked)
    {
        inputLoanInterestLabel.innerHTML = "Loan Interest Amount";
        inputLoanInterest.placeholder = "Amount";
    }
}

$('input[type=radio][name=after_maturity_extend_loan]').on('change', function () {
	enableDisableExtendLoan();
	checkAMRadio();
});
function enableDisableExtendLoan()
{
    if ($("#inputExtendLoanYes").prop("checked"))
    {
		$('input[name="after_maturity_percentage_or_fixed"]').prop('disabled', false);
  		$('#inputAmCalculateInterestOn').prop('disabled', false);
  		$('#inputAmInterest').prop('disabled', false);
  		$('#inputAmLoanPaymentSchemeId').prop('disabled', false);
  		$('#inputAmRecurringPeriod').prop('disabled', false);
		$('input[name="after_maturity_include_fees"]').prop('disabled', false);
    }
    else if ($("#inputExtendLoanNo").prop("checked"))
    {
		$('input[name="after_maturity_percentage_or_fixed"]').prop('disabled', true);
  		$('#inputAmCalculateInterestOn').prop('disabled', true);
  		$('#inputAmInterest').prop('disabled', true);
  		$('#inputAmLoanPaymentSchemeId').prop('disabled', true);
  		$('#inputAmRecurringPeriod').prop('disabled', true);
		$('input[name="after_maturity_include_fees"]').prop('disabled', true);
    }
}
function checkAMRadio() 
{
	var val = $("input[name=after_maturity_percentage_or_fixed]:checked").val();
    if (val == "percentage")
    {
        $("#inputAMCalculateInterestOnLabel").text("Calculate Interest on");
        $("#inputAMInterestOrFixedLabel").text("Loan Interest Rate After Maturity %");
        $("#inputAmInterest").removeClass('decimal-2-places');
        $("#inputAmInterest").addClass('decimal-4-places');
        $(".decimal-4-places").numeric({ decimalPlaces: 4 });
    }
    else if (val == "fixed")
    {
        $("#inputAMCalculateInterestOnLabel").text("Calculate Interest if there is");
        $("#inputAMInterestOrFixedLabel").text("Loan Interest Amount After Maturity");
        $("#inputAmInterest").removeClass('decimal-4-places');
        $("#inputAmInterest").addClass('decimal-2-places');
        
        $(".decimal-2-places").numeric({ decimalPlaces: 2 });
    }
}
$('input[type=radio][name=after_maturity_percentage_or_fixed]').on('change', function () {
	checkAMRadio();
});
enableDisableExtendLoan();
checkAMRadio();