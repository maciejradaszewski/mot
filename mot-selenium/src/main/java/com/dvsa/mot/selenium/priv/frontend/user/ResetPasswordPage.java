package com.dvsa.mot.selenium.priv.frontend.user;


import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class ResetPasswordPage extends BasePage{

    private String PAGE_TITLE = "MOT TESTING SERVICE\n" + "PASSWORD RESET";

    @FindBy(className = "lede") private WebElement arrivalMessage;

    @FindBy(className = "validation-message") private WebElement passwordValidationMessage;


    @FindBy(id = "validation-summary-message") private WebElement validationMessage;


    @FindBy(id = "password") private WebElement password;

    @FindBy(id = "passwordConfirm") private WebElement passwordConfirm;

    @FindBy(id = "submitPass") private WebElement submit;

    public ResetPasswordPage(WebDriver driver){
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public ResetPasswordPage enterPassword(String newPassword){
        password.sendKeys(newPassword);
        return new ResetPasswordPage(driver);
    }

    public ResetPasswordPage enterConfirmPassword(String confirmPassword){
        passwordConfirm.sendKeys(confirmPassword);
        return new ResetPasswordPage(driver);
    }

    public UserDashboardPage submitPassword(){
        submit.click();
        return new UserDashboardPage(driver);
    }

    public String passwordValidation(){
       return passwordValidationMessage.getText();
    }
    public void clearPasswordFields(){
        password.clear();
        passwordConfirm.clear();

    }

}
