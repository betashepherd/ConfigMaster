package main

import (
	"encoding/json"
	"fmt"
	"log"
	"net/http"
	"runtime"
	"strconv"
	"time"

	"gopkg.in/mgo.v2"
	"gopkg.in/mgo.v2/bson"
)

//RespStatus json status
type RespStatus struct {
	Code int    `json:"code"`
	Msg  string `json:"msg"`
}

//AppInfo APP info
type AppInfo struct {
	ID   string `json:"id"`
	Name string `json:"name"`
}

//RespAppInfo cfg resp
type RespAppInfo struct {
	Status RespStatus `json:"status"`
	Data   AppInfo    `json:"data,omitempty"`
}

//Cfg config item
type Cfg struct {
	Key  string `json:"key,omitempty"`
	Val  string `json:"val,omitempty"`
	Path string `json:"path,omitempty"`
	Ver  int    `json:"ver,omitempty"`
}

//RespCfg cfg resp
type RespCfg struct {
	Status RespStatus `json:"status"`
	Data   Cfg        `json:"data,omitempty"`
}

//AppCfg app config list
type AppCfg struct {
	Cfgs []Cfg `json:"cfgs,omitempty"`
}

//RespAppCfgs cfg resp
type RespAppCfgs struct {
	Status RespStatus `json:"status"`
	Data   []*Cfg     `json:"data,omitempty"`
}

//MgConfigItem conifg item in mongodb
type MgConfigItem struct {
	ID        bson.ObjectId `bson:"_id,omitempty"`
	AppID     string
	Path      string
	Key       string
	Value     string
	Version   int
	Timestamp int
	Operator  string
	Desc      string
	Status    int
}

//MgAppItem app struct in mongodb
type MgAppItem struct {
	ID        bson.ObjectId `bson:"_id,omitempty"`
	Name      string
	Desc      string
	Operator  string
	Timestamp int
}

//RespJSON server response json data
func RespJSON(w http.ResponseWriter, rawJSON string) {
	w.Header().Set("Server", "ConfigMaster")
	w.Header().Set("Content-Type", "application/json; charset=utf-8")
	fmt.Fprintf(w, string(rawJSON))
}

func indexHandler(w http.ResponseWriter, r *http.Request) {
	w.Header().Set("Server", "ConfigMaster")
	w.Header().Set("Content-Type", "text/html")
	fmt.Fprintf(w, "<center><i>Welcome to ConfigMaster</i></center>")
}

func appHandler(w http.ResponseWriter, r *http.Request) {
	appid := r.URL.Query().Get("id")

	session, err := mgo.Dial("localhost")
	if err != nil {
		fmt.Println(err)
	}

	defer session.Close()
	var result MgAppItem
	session.SetMode(mgo.Monotonic, true)
	c := session.DB("configmaster").C("app")
	merr := c.Find(bson.M{"_id": bson.ObjectIdHex(appid)}).One(&result)

	fmt.Println(result)

	if merr != nil {
		fmt.Println(merr)
	}

	//init resp status
	status := new(RespStatus)
	status.Code = 0
	status.Msg = ""
	appInfo := new(AppInfo)
	appInfo.ID = appid
	appInfo.Name = result.Name

	Resp := new(RespAppInfo)
	Resp.Status = *status
	Resp.Data = *appInfo
	rawJSON, _ := json.Marshal(Resp)
	RespJSON(w, string(rawJSON))
}

func appCfgHandler(w http.ResponseWriter, r *http.Request) {

	appid := r.URL.Query().Get("id")
	session, err := mgo.Dial("localhost")
	if err != nil {
		log.Fatal(err)
	}

	defer session.Close()
	var result []MgConfigItem
	session.SetMode(mgo.Monotonic, true)
	c := session.DB("configmaster").C("config")
	merr := c.Find(bson.M{"appid": appid}).All(&result)

	//fmt.Println(result)

	if merr != nil {
		fmt.Println(merr)
	}

	//init resp status
	status := new(RespStatus)
	status.Code = 0
	status.Msg = ""

	Resp := new(RespAppCfgs)
	Resp.Status = *status
	Resp.Data = []*Cfg{}
	for _, c := range result {
		//init cfg
		cfg := new(Cfg)
		cfg.Key = c.Key
		cfg.Path = c.Path
		cfg.Ver = c.Version
		Resp.Data = append(Resp.Data, cfg)
	}
	rawJSON, _ := json.Marshal(Resp)
	RespJSON(w, string(rawJSON))
}

func cfgHandler(w http.ResponseWriter, r *http.Request) {
	appid := r.URL.Query().Get("id")
	key := r.URL.Query().Get("k")
	ver := r.URL.Query().Get("v")
	version, _ := strconv.Atoi(ver)

	session, err := mgo.Dial("localhost")
	if err != nil {
		fmt.Println(err)
	}

	defer session.Close()
	var result MgConfigItem
	session.SetMode(mgo.Monotonic, true)
	c := session.DB("configmaster").C("config_his")
	merr := c.Find(bson.M{"appid": appid, "key": key, "version": version, "status": 1}).One(&result)

	//fmt.Println(result)

	if merr != nil {
		fmt.Println(merr)
	}

	//init resp status
	status := new(RespStatus)
	status.Code = 0
	status.Msg = ""

	cfg := new(Cfg)
	//cfg.Key = result.Key
	cfg.Path = result.Path
	cfg.Val = result.Value
	//cfg.Ver = result.Version
	Resp := new(RespCfg)
	Resp.Status = *status
	Resp.Data = *cfg
	rawJSON, _ := json.Marshal(Resp)
	RespJSON(w, string(rawJSON))
}

func main() {
	runtime.GOMAXPROCS(runtime.NumCPU())
	http.HandleFunc("/", indexHandler)
	http.HandleFunc("/app", appHandler)
	http.HandleFunc("/appcfg", appCfgHandler)
	http.HandleFunc("/getcfg", cfgHandler)
	now := time.Now().Format("2006-01-02 15:04:05")
	fmt.Println(now, "Server Start : 0.0.0.0:8181")
	err := http.ListenAndServe("0.0.0.0:8181", nil)
	if err != nil {
		fmt.Println(err)
	}
}
