package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.datasource.Text;
import org.testng.Assert;

import com.dvsa.mot.selenium.datasource.enums.VehicleModel;

public class VerifyWelshCertificateDetails
{
    String notValidText = "NOT VALID";
    public String getTitle(String parsedText)
    {
        int startIndex = parsedText.indexOf(Text.TEXT_NOT_VALID);
        int lastIndex = parsedText.lastIndexOf(Text.TEXT_MOT_TEST_NUMBER);
        return parsedText.substring(startIndex+Text.TEXT_NOT_VALID.length()+1, lastIndex-1);
    }
    public String getVT32Title(String parsedText)
    {
        int startIndex = parsedText.indexOf(Text.TEXT_VT20);
        int lastIndex = parsedText.lastIndexOf(notValidText);
        return parsedText.substring(startIndex+9, lastIndex);
    }

    public String getVT20VT30TestNumber(String parsedText)
    {
        int startIndex = parsedText.lastIndexOf("MOT Test Number / Rhif Prawf MOT");
        int lastIndex = parsedText.lastIndexOf("Registration Mark / Marc Cofrestru");
        return parsedText.substring(startIndex+33,lastIndex-1);
    }

    public String getVT20VT30Make(String parsedText)
    {
        int startIndex = parsedText.indexOf("Make / Gwneuthuriad");
        int lastIndex = parsedText.indexOf("Model");
        return parsedText.substring(startIndex+20,lastIndex-1);
    }

    public String getVT20VT30VRM(String parsedText)
    {
        int startIndex = parsedText.indexOf("Registration Mark / Marc Cofrestru");
        int lastIndex = parsedText.indexOf("Vehicle Identification Number");
        return parsedText.substring(startIndex+35,lastIndex-1);
    }

    public String getVT32TestNumberMake(String parsedText)
    {
        int startIndex = parsedText.lastIndexOf("NOT VALID");
        int lastIndex = parsedText.lastIndexOf("Make");
        return parsedText.substring(startIndex,lastIndex);
    }
    public String getVT32VRM(String parsedText)
    {
        int startIndex = parsedText.lastIndexOf("Vehicle Registration Mark");
        int lastIndex = parsedText.lastIndexOf("Model");

        return parsedText.substring(startIndex+26, lastIndex-1);
    }
    public int getPageCount(String parsedText)
    {
        parsedText = parsedText.trim();
        int length = parsedText.length();

        String pageCount = parsedText.substring(length-9, length-8);
        return Integer.parseInt(pageCount);
    }
    public int getWelshPageCount(String parsedText){
        parsedText = parsedText.trim();
        int length = parsedText.length();

        String pageCount = parsedText.substring(length-10, length-9);
        return Integer.parseInt(pageCount);
    }
    public int getnthPageLastIndex(String parsedText,int pageN, int pageCount)
    {
        parsedText = parsedText.trim();

        return (parsedText.lastIndexOf("Page "+pageN+" of "+pageCount));
    }
    public String getCertificateTypes(String parsedText)
    {
        parsedText = parsedText.trim();
        int length = parsedText.length();

        String certificateType = parsedText.substring(length-8, length-4);
        return certificateType;
    }
    public String getWelshCertificateTypes(String parsedText)
    {
        parsedText = parsedText.trim();
        int length = parsedText.length();

        String certificateType = parsedText.substring(length-9, length-5);
        return certificateType;
    }
    public String getVinModelColour(String parsedText)
    {
        int startIndex = parsedText.lastIndexOf("Vehicle Identification Number");
        int lastIndex = parsedText.indexOf("Issuer's Name / Enw'r Cyhoeddwr");

        return parsedText.substring(startIndex, lastIndex);
    }
}
