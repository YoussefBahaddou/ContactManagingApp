package com.emsi.contactmanagingtp;

import com.google.gson.annotations.SerializedName;

public class Contact {
    @SerializedName("id")
    private String id; // or int, depending on your existing implementation
    
    @SerializedName("name")
    private String name;
    
    @SerializedName("number")
    private String number;
    
    private String photoUri;

    public Contact(String name, String number) {
        this.name = name;
        this.number = number;
        this.photoUri = photoUri;
    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getNumber() {
        return number;
    }

    public void setNumber(String number) {
        this.number = number;
    }

    public String getPhotoUri() {
        return photoUri;
    }

    public String getPhoneNumber() {
        return number;
    }
}
