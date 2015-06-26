package uk.gov.dvsa.domain.model;

public class AeContactDetails {

    private String email;
    private String confirmationEmail;
    private String telephoneNumber;

    public AeContactDetails(String email, String confirmationEmail, String telephoneNumber) {
        this.email = email;
        this.confirmationEmail = email;
        this.telephoneNumber = telephoneNumber;
    }

    public String getEmail() {
        return email;
    }

    public String getConfirmationEmail() {
        return confirmationEmail;
    }

    public String getTelephoneNumber() {
        return telephoneNumber;
    }

    @Override public String toString() {
        return "AeContactDetails{" +
            "email='" + email + '\'' +
            ", confirmationEmail='" + confirmationEmail + '\'' +
            ", telephoneNumber='" + telephoneNumber + '\'' +
            '}';
    }
}
