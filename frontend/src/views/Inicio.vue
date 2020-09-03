<template>
    <v-main>
        <v-container>
            <div>
                <div class="box">
                    <a class="login" @click="$router.push('/login')">
                        <div>
                            <div class="item">
                                <div>
                                    <svg width="134" height="142" id="Capa_1" enable-background="new 0 0 512 512"
                                        viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path
                                                d="m344.56 482-4.6 15.67h-167.46l-5.06-15.67c30.5 0 55.23-24.73 55.23-55.24v-40.17h66.66v40.17c0 30.51 24.73 55.24 55.23 55.24z"
                                                fill="#cddbf9" />
                                            <path
                                                d="m344.56 482-4.6 15.67h-83.96v-111.08h33.33v40.17c0 30.51 24.73 55.24 55.23 55.24z"
                                                fill="#afc2e8" />
                                            <path d="m512 340.98v75.61h-512v-75.61l15-15h482z" fill="#f3f6ff" />
                                            <path d="m512 340.98v75.61h-256v-90.61h241z" fill="#cddbf9" />
                                            <path d="m15 15h482v310.98h-482z" fill="#48588b" />
                                            <path d="m256 15h241v310.98h-241z" fill="#3c4a83" />
                                            <path
                                                d="m311.98 120.949v28.88h-30v-28.88c0-14.32-11.66-25.97-25.98-25.97s-25.98 11.65-25.98 25.97v28.88h-30v-28.88c0-30.86 25.11-55.97 55.98-55.97s55.98 25.11 55.98 55.97z"
                                                fill="#cddbf9" />
                                            <path
                                                d="m311.98 120.949v28.88h-30v-28.88c0-14.32-11.66-25.97-25.98-25.97v-30c30.87 0 55.98 25.11 55.98 55.97z"
                                                fill="#afc2e8" />
                                            <path d="m176.09 134.829v147.48h64.91l15-6.19 15 6.19h64.91v-147.48z"
                                                fill="#fea000" />
                                            <path d="m335.91 134.829v147.48h-64.91l-15-6.19v-141.29z" fill="#f78002" />
                                            <path d="m241 192.359h30v89.95h-30z" fill="#fbe200" />
                                            <path d="m256 192.359h15v89.95h-15z" fill="#febf01" />
                                            <path d="m132.33 482h247.34v30h-247.34z" fill="#f3f6ff" />
                                            <path d="m256 482h123.67v30h-123.67z" fill="#cddbf9" />
                                            <path d="m0 0v340.98h512v-340.98zm482 310.98h-452v-280.98h452z"
                                                fill="#3c4a83" />
                                            <path d="m512 0v340.98h-256v-30h226v-280.98h-226v-30z" fill="#2a386b" />
                                        </g>
                                    </svg>
                                </div>
                                <p>Entrar</p>
                            </div>
                        </div>
                    </a>
                    <a class="dashboard" @click="$router.push('/dashboard')">
                        <div>
                            <div class="item">
                                <div>
                                    <!-- Generator: Adobe Illustrator 19.0.0, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->
                                    <svg width="134" height="142" version="1.1" id="Capa_1"
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        x="0px" y="0px" viewBox="0 0 60 60" style="enable-background:new 0 0 60 60;"
                                        xml:space="preserve">
                                        <g>
                                            <rect x="32" y="7" style="fill:#14A085;" width="10" height="45" />
                                            <rect x="17" y="38" style="fill:#F29C1F;" width="10" height="14" />
                                            <rect x="2" y="29" style="fill:#E57E25;" width="10" height="23" />
                                            <rect x="47" y="15" style="fill:#71C285;" width="10" height="37" />
                                            <path style="fill:#556080;"
                                                d="M59,53H1c-0.552,0-1-0.447-1-1s0.448-1,1-1h58c0.552,0,1,0.447,1,1S59.552,53,59,53z" />
                                        </g>
                                    </svg>
                                </div>
                                <p>Dashboard</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </v-container>
    </v-main>
</template>

<script>
    export default {
        data() {
            return {

            }
        }
    }
</script>

<style scoped>
    .container {
        display: flex;
        -webkit-box-pack: center;
        justify-content: center;
    }

    .container>div {
        width: 100%;
        max-width: 1426px;
        padding: 30px;
    }

    .box {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-template-rows: minmax(650px, auto);
        gap: 24px;
    }

    .box a {
        display: flex;
    }

    .box .login>div:hover {
        transform: translateY(-7px);
        border-color: rgb(0 0 0);
    }

    .box .dashboard>div:hover {
        transform: translateY(-7px);
        border-color: rgb(0 0 0);
    }

    .box a>div {
        background: #90CAF9;
        position: relative;
        border-radius: 8px;
        transition: all 0.2s ease 0s;
        padding: 64px;
        display: flex;
        width: 100%;
        flex-direction: column;
        -webkit-box-pack: justify;
        justify-content: space-between;
        border: 2px solid #90CAF9;
    }

    .box a .item {
        flex: 1 1 0%;
        display: flex;
        flex-direction: column;
        -webkit-box-align: center;
        align-items: center;
    }

    .box a .item p {
        max-width: 270px;
        text-align: center;
        font-size: 2.5rem;
        line-height: 26px;
        font-weight: lighter;
    }

    .box a .item>div {
        display: flex;
        -webkit-box-align: center;
        align-items: center;
        -webkit-box-pack: center;
        justify-content: center;
        height: 145px;
        margin: 105px 0px 55px;
    }

    .box .login p {
        color: rgb(0 0 0);
    }

    .box .dashboard p {
        color: rgb(0 0 0);
    }

    @media (max-width: 768px) {
        .container>div {
            padding: 20px;
        }

        .box a>div {
            padding: 30px;
        }
    }

    @media (max-height: 825px) {
        .box {
            grid-template-rows: minmax(600px, auto);
        }
    }

    @media (max-width: 1120px) {
        .box {
            grid-template-columns: 1fr;
            grid-template-rows: repeat(3, minmax(300px, auto));
        }

        .box a>div {
            background-position: center bottom;
            padding: 30px;
        }

        .box a .item>div {
            margin: 10px 0px 20px;
            height: auto;
        }

        .box svg {
            margin: 10px 0px 20px;
            height: auto;
        }
    }
</style>