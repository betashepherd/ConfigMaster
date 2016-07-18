package utils

import (
	"log"
	"os"
)

var (
	Trace *log.Logger
	Info *log.Logger
	Warning *log.Logger
	Error *log.Logger
)

func Logger_init(file_name string) bool {
	file, err := os.OpenFile(file_name, os.O_CREATE|os.O_WRONLY|os.O_APPEND,0666)
	if err != nil {
		log.Fatalln("Failed to open log file:", err)
		return false
	}
	// defer file.Close()

	Trace = log.New(file, "TRACE:", log.Ldate|log.Ltime|log.Lshortfile)
	Info = log.New(file, "INFO:", log.Ldate|log.Ltime|log.Lshortfile)
	Warning = log.New(file,"WARNING:", log.Ldate|log.Ltime|log.Lshortfile)
	Error = log.New(file, "ERROR:",log.Ldate|log.Ltime|log.Lshortfile)
	return true
}

func Trace_Log(msg string) {
	Trace.Println(msg)
}