package uk.gov.dvsa.domain.model;

public class QualificationCertificate {
    protected String id;
    protected String siteNumber;
    protected String vehicleClassGroupCode;
    protected String certificateNumber;
    protected String dateOfQualification;

    public QualificationCertificate(){}

    public QualificationCertificate(
            String dateOfQualification,
            String vehicleClassGroupCode,
            String certificateNumber,
            String siteNumber,
            String id
    ){
        this.id = id;
        this.siteNumber = siteNumber;
        this.vehicleClassGroupCode = vehicleClassGroupCode;
        this.certificateNumber = certificateNumber;
        this.dateOfQualification = dateOfQualification;
    }

    public String getId() {
        return id;
    }

    public String getSiteNumber() {
        return siteNumber;
    }

    public String getVehicleClassGroupCode() {
        return vehicleClassGroupCode;
    }

    public String getCertificateNumber() {
        return certificateNumber;
    }

    public String getDateOfQualification() {
        return dateOfQualification;
    }
}
