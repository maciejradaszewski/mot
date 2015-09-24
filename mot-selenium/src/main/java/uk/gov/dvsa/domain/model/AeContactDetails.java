package uk.gov.dvsa.domain.model;

public class AeContactDetails {

    private String email;
    private String confirmationEmail;
    private String telephoneNumber;
    private Address address;

    public AeContactDetails(String email, String confirmationEmail, String telephoneNumber) {
        this.email = email;
        this.confirmationEmail = confirmationEmail;
        this.telephoneNumber = telephoneNumber;
        address = new Address();
    }

    public Address getAddress() {
        return address;
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

    @Override
    public String toString() {
        return "AeContactDetails{" +
                "email='" + email + '\'' +
                ", confirmationEmail='" + confirmationEmail + '\'' +
                ", telephoneNumber='" + telephoneNumber + '\'' +
                '}';
    }
}
